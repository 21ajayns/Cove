<?php
declare(strict_types=1);

namespace Tests\Feature\Http;

use App\Http\Middleware\LocalMode;
use App\Models\Task\Task;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class LocalModeTest extends TestCase
{
    use RefreshDatabase;

    private const TOKEN = 'launch-token-for-tests';

    public function testNothingChangesWhenLocalModeIsOff(): void
    {
        $this->request('GET', '/api/notes', null, self::TOKEN)->assertUnauthorized();
        $this->assertDatabaseCount('users', 0);
    }

    public function testRequestsWithoutTheLaunchTokenAreRefused(): void
    {
        $this->enableLocalMode();

        $this->request('GET', '/api/notes', null, null)->assertForbidden();
        $this->request('GET', '/api/notes', null, 'wrong-token')->assertForbidden();
        $this->request('GET', '/', null, null)->assertForbidden();

        $this->assertDatabaseCount('users', 0);
    }

    public function testLocalModeRefusesEverythingWhenNoTokenIsConfigured(): void
    {
        \config(['cove.local' => true, 'cove.local_token' => null]);

        $this->request('GET', '/api/notes', null, '')->assertForbidden();
        $this->request('GET', '/api/notes', null, null)->assertForbidden();
    }

    public function testRequestsWithTheLaunchTokenAreSignedInAsTheLocalUser(): void
    {
        $this->enableLocalMode();

        $this->request('GET', '/api/notes', null, self::TOKEN)->assertOk();

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['email' => 'local@cove.local', 'name' => 'Tester']);
        $this->assertDatabaseHas('organizations', ['name' => 'Tester']);
    }

    public function testTheLocalUserIsReusedAndOwnsWhatItCreates(): void
    {
        $this->enableLocalMode();

        $this->request('POST', '/api/tasks', ['title' => 'Local task', 'category' => 'office'], self::TOKEN)->assertCreated();
        $this->request('GET', '/api/tasks?category=office', null, self::TOKEN)
            ->assertOk()
            ->assertJsonFragment(['title' => 'Local task']);

        $this->assertDatabaseCount('users', 1);
        $this->assertSame(
            User::query()->firstOrFail()->getOrganizationId(),
            Task::query()->firstOrFail()->getAttribute('organization_id')
        );
    }

    public function testTheHomePageOpensWithoutALogin(): void
    {
        $this->enableLocalMode();

        $this->request('GET', '/', null, self::TOKEN)
            ->assertOk()
            ->assertDontSee('Log out');

        $this->request('GET', '/login', null, self::TOKEN)->assertRedirect('/');
    }

    private function enableLocalMode(): void
    {
        \config([
            'cove.local' => true,
            'cove.local_token' => self::TOKEN,
            'cove.local_name' => 'Tester',
        ]);
    }

    /**
     * getJson()/postJson() do not send test cookies, so build the request by hand.
     *
     * @param array<string, mixed>|null $body
     */
    private function request(string $method, string $uri, ?array $body, ?string $token): TestResponse
    {
        $cookies = $token === null ? [] : [LocalMode::COOKIE => $token];

        return $this->call(
            $method,
            $uri,
            [],
            $cookies,
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_ACCEPT' => 'application/json'],
            $body === null ? null : (string) \json_encode($body)
        );
    }
}
