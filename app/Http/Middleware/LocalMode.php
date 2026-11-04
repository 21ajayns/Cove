<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Organization\Organization;
use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Desktop app only: signs every request in as the one local user, and refuses
 * any request that does not carry the launch token the desktop window set.
 */
class LocalMode
{
    public const COOKIE = 'cove_local';

    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\config('cove.local') !== true) {
            return $next($request);
        }

        $token = (string) \config('cove.local_token');
        $given = (string) $request->cookies->get(self::COOKIE, '');

        if ($token === '' || \hash_equals($token, $given) === false) {
            \abort(403, 'Cove can only be opened from its own window.');
        }

        Auth::guard('web')->setUser($this->localUser());

        return $next($request);
    }

    private function localUser(): User
    {
        $email = (string) \config('cove.local_email');

        $user = User::query()->where('email', $email)->first();

        if ($user instanceof User) {
            return $user;
        }

        $name = (string) \config('cove.local_name');

        $organization = Organization::query()->create(['name' => $name]);

        return User::query()->create([
            'organization_id' => $organization->getId(),
            'name' => $name,
            'email' => $email,
            'password' => Str::random(40),
        ]);
    }
}
