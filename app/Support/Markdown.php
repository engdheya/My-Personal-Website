<?php

namespace App\Support;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Article body renderer.
 *
 * Replaces the Node engine pipeline (marked + highlight.js + custom renderer)
 * with league/commonmark, which ships with Laravel 11, while keeping the exact
 * same HTML output contract: heading anchors, a code wrapper with a copy
 * button, and a table of contents built from <h2>/<h3>.
 */
class Markdown
{
    /**
     * Render Markdown to the site's HTML flavour.
     */
    public static function toHtml(?string $markdown): string
    {
        if (! is_string($markdown) || trim($markdown) === '') {
            return '';
        }

        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 25,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $converter = new MarkdownConverter($environment);

        $html = (string) $converter->convert($markdown);

        return static::decorate($html);
    }

    /**
     * Build the table of contents from the raw Markdown body.
     *
     * @return array<int, array{level:int, text:string, id:string}>
     */
    public static function toc(?string $markdown): array
    {
        if (! is_string($markdown) || trim($markdown) === '') {
            return [];
        }

        $items = [];

        if (preg_match_all('/^(#{2,3})\s+(.+)$/m', $markdown, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $text = trim($match[2]);
                $items[] = [
                    'level' => strlen($match[1]),
                    'text' => static::plainText($text),
                    'id' => static::anchor($text),
                ];
            }
        }

        return $items;
    }

    /**
     * Add heading anchors/classes and wrap code blocks with the copy button UI.
     */
    protected static function decorate(string $html): string
    {
        // 1. Headings: <h2>Title</h2> => <h2 id="anchor" class="..."><a ...>Title</a></h2>
        $html = preg_replace_callback(
            '/<h([23])>(.*?)<\/h\1>/s',
            function (array $match): string {
                $level = (int) $match[1];
                $inner = $match[2];
                $id = static::anchor(static::plainText($inner));

                if ($id === '') {
                    return $match[0];
                }

                $classes = $level === 2
                    ? 'text-2xl mt-8 mb-4'
                    : 'text-xl mt-6 mb-3';

                return '<h'.$level.' id="'.$id.'" class="scroll-mt-20 group relative font-bold text-slate-900 dark:text-white '.$classes.'">'
                    .'<a href="#'.$id.'" class="text-inherit hover:underline">'.$inner.'</a>'
                    .'</h'.$level.'>';
            },
            $html
        ) ?? $html;

        // 2. Code blocks: wrap with header + 1-click copy button (always LTR).
        $html = preg_replace_callback(
            '/<pre><code(?: class="language-([^"]*)")?>(.*?)<\/code><\/pre>/s',
            function (array $match): string {
                $language = $match[1] ?? '';
                $language = preg_replace('/[^A-Za-z0-9_+#-]/', '', (string) $language) ?: 'plaintext';
                $code = $match[2];

                return '<div class="code-wrapper not-prose my-6 rounded-lg overflow-hidden border border-slate-700 bg-slate-950 text-slate-100 shadow-md" dir="ltr">'
                    .'<div class="code-header flex items-center justify-between px-4 py-2 bg-slate-900 border-b border-slate-800 text-xs text-slate-400 font-mono">'
                    .'<span>'.strtoupper($language).'</span>'
                    .'<button type="button" onclick="copyCode(this)" class="copy-btn px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors text-xs flex items-center gap-1 cursor-pointer">'
                    .'<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>'
                    .'<span>Copy</span>'
                    .'</button>'
                    .'</div>'
                    .'<pre class="p-4 overflow-x-auto text-sm font-mono leading-relaxed"><code class="hljs language-'.$language.'">'.$code.'</code></pre>'
                    .'</div>';
            },
            $html
        ) ?? $html;

        return $html;
    }

    /**
     * Slug used for heading anchors (Latin + Arabic friendly).
     */
    public static function anchor(?string $text): string
    {
        $text = static::plainText((string) $text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}_]+/u', '-', $text) ?? '';
        $text = trim($text, '-');

        return $text;
    }

    /**
     * Strip tags/backticks/emphasis markers to get plain readable text.
     */
    protected static function plainText(string $text): string
    {
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(['`', '*', '_', '~'], '', $text);

        return trim($text);
    }
}
