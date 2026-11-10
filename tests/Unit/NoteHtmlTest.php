<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Support\NoteHtml;
use PHPUnit\Framework\TestCase;

class NoteHtmlTest extends TestCase
{
    public function test_plain_text_is_left_untouched(): void
    {
        $plain = "Line one\nR&D <b> & more";

        $this->assertSame($plain, NoteHtml::clean($plain));
    }

    public function test_allowed_formatting_is_kept(): void
    {
        $html = '<div class="nr"><div>Hi <code>npm test</code> <span style="color: rgb(239, 68, 68)">red</span></div>'
            . '<ol><li>one<ol><li>sub</li></ol></li></ol><ul><li>dot</li></ul></div>';

        $this->assertSame($html, NoteHtml::clean($html));
    }

    public function test_scripts_handlers_and_bad_urls_are_removed(): void
    {
        $dirty = '<div class="nr"><script>alert(1)</script><div onclick="x()">a</div>'
            . '<a href="javascript:alert(1)">bad</a><a href="https://ok.io/p?q=1&amp;r=2" onclick="x()">ok</a>'
            . '<img src=x onerror=alert(1)><span style="color: red; background: url(x)">c</span>'
            . '<span style="color: #ef4444; position: fixed">d</span></div>';

        $clean = NoteHtml::clean($dirty);

        $this->assertStringNotContainsString('script', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('javascript', $clean);
        $this->assertStringNotContainsString('<img', $clean);
        $this->assertStringNotContainsString('url(', $clean);
        $this->assertStringNotContainsString('position', $clean);
        $this->assertStringContainsString('<a href="https://ok.io/p?q=1&amp;r=2" target="_blank" rel="noopener noreferrer">ok</a>', $clean);
        $this->assertStringContainsString('<span style="color: #ef4444">d</span>', $clean);
        $this->assertStringContainsString('<div>a</div>', $clean);
    }

    public function test_nested_lists_as_the_editor_produces_them_survive(): void
    {
        $html = '<div class="nr"><ol><li>a</li><li>b</li><ol><li>sub</li></ol><li>c</li></ol></div>';

        $this->assertSame($html, NoteHtml::clean($html));
    }

    public function test_zero_width_spaces_are_stripped(): void
    {
        $this->assertSame(
            '<div class="nr"><div><code>x</code> y</div></div>',
            NoteHtml::clean("<div class=\"nr\"><div><code>x</code>\u{200B} y</div></div>")
        );
    }

    public function test_text_is_escaped_and_wrapper_is_not_doubled(): void
    {
        $clean = NoteHtml::clean('<div class="nr"><div>1 &lt; 2 &amp; "q"</div></div>');

        $this->assertSame('<div class="nr"><div>1 &lt; 2 &amp; &quot;q&quot;</div></div>', $clean);
    }
}
