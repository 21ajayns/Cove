<?php
declare(strict_types=1);

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Cleans the rich-text a note is saved with.
 *
 * The note editor stores formatted notes as HTML wrapped in <div class="nr">. Anything
 * else (older notes, API clients) is plain text and is stored exactly as given. Rich
 * content is rebuilt from a small whitelist (bold/italic/underline, lists, code, links,
 * a text colour), so a saved note can never carry scripts, event handlers or odd URLs.
 */
final class NoteHtml
{
    public const WRAPPER_OPEN = '<div class="nr">';

    private const WRAPPER_CLOSE = '</div>';

    private const KEEP = ['b', 'strong', 'i', 'em', 'u', 's', 'p', 'div', 'span', 'ul', 'ol', 'li', 'code', 'a', 'br'];

    private const DROP_WITH_CONTENT = ['script', 'style', 'iframe', 'object', 'embed', 'template', 'noscript', 'head', 'title', 'meta', 'link', 'svg', 'math'];

    private const LIST_STYLES = ['none', 'decimal', 'disc', 'circle', 'square', 'lower-alpha', 'upper-alpha', 'lower-roman', 'upper-roman', 'lower-latin', 'upper-latin'];

    public static function isRich(string $content): bool
    {
        return str_starts_with($content, self::WRAPPER_OPEN);
    }

    /** Sanitises rich content; plain text is returned untouched. */
    public static function clean(string $content): string
    {
        if (! self::isRich($content)) {
            return $content;
        }

        $doc = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $doc->loadHTML('<?xml encoding="utf-8"?><body>' . $content . '</body>', LIBXML_NONET | LIBXML_NOWARNING | LIBXML_NOERROR);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $body = $doc->getElementsByTagName('body')->item(0);
        $root = $body?->firstChild;

        // Our own wrapper is the only top-level node; clean what is inside it and add exactly one back.
        $html = $root instanceof DOMElement && $body->childNodes->length === 1
            ? self::children($root)
            : ($body ? self::children($body) : '');

        return self::WRAPPER_OPEN . $html . self::WRAPPER_CLOSE;
    }

    private static function children(DOMNode $node): string
    {
        $out = '';
        foreach ($node->childNodes as $child) {
            $out .= self::node($child);
        }

        return $out;
    }

    private static function node(DOMNode $node): string
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            // Zero-width spaces are only used by the editor as invisible caret anchors.
            return htmlspecialchars(str_replace("\u{200B}", '', $node->nodeValue ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        if (! $node instanceof DOMElement) {
            return ''; // comments, processing instructions, ...
        }

        $tag = strtolower($node->tagName);

        if (in_array($tag, self::DROP_WITH_CONTENT, true)) {
            return '';
        }

        // Old-style <font color="..."> becomes a coloured span.
        if ($tag === 'font') {
            $color = self::color($node->getAttribute('color'));

            return $color === null
                ? self::children($node)
                : '<span style="color: ' . $color . '">' . self::children($node) . '</span>';
        }

        if (! in_array($tag, self::KEEP, true)) {
            return self::children($node); // unknown tag: keep its text, drop the tag
        }

        if ($tag === 'br') {
            return '<br>';
        }

        $inner = self::children($node);

        switch ($tag) {
            case 'a':
                $href = trim($node->getAttribute('href'));
                if (preg_match('#^https?://[^\s"<>]+$#i', $href) !== 1) {
                    return $inner;
                }

                return '<a href="' . htmlspecialchars($href, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . $inner . '</a>';

            case 'span':
                $color = self::styleColor($node->getAttribute('style'));

                return $color === null ? $inner : '<span style="color: ' . $color . '">' . $inner . '</span>';

            case 'ul':
            case 'ol':
                $attrs = self::listStyleAttr($node);
                if ($tag === 'ol' && preg_match('/^\d{1,4}$/', $node->getAttribute('start')) === 1) {
                    $attrs .= ' start="' . $node->getAttribute('start') . '"';
                }

                return '<' . $tag . $attrs . '>' . $inner . '</' . $tag . '>';

            case 'li':
                return '<li' . self::listStyleAttr($node) . '>' . $inner . '</li>';
        }

        return '<' . $tag . '>' . $inner . '</' . $tag . '>';
    }

    private static function listStyleAttr(DOMElement $node): string
    {
        if (preg_match('/list-style-type\s*:\s*([a-z-]+)/i', $node->getAttribute('style'), $m) === 1
            && in_array(strtolower($m[1]), self::LIST_STYLES, true)) {
            return ' style="list-style-type: ' . strtolower($m[1]) . '"';
        }

        return '';
    }

    private static function styleColor(string $style): ?string
    {
        if (preg_match('/(?:^|;)\s*color\s*:\s*([^;]+)/i', $style, $m) !== 1) {
            return null;
        }

        return self::color($m[1]);
    }

    private static function color(string $value): ?string
    {
        $value = strtolower(trim($value));

        if (preg_match('/^#[0-9a-f]{3,8}$/', $value) === 1
            || preg_match('/^rgba?\(\s*\d{1,3}\s*,\s*\d{1,3}\s*,\s*\d{1,3}\s*(,\s*[\d.]+\s*)?\)$/', $value) === 1) {
            return $value;
        }

        return null;
    }
}
