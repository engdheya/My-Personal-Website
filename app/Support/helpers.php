<?php

use App\Models\Setting;
use App\Support\Markdown;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;

/*
|--------------------------------------------------------------------------
| Global helpers (Blade + Controllers)
|--------------------------------------------------------------------------
|
| These helpers keep the Blade templates short and readable, and they are
| completely safe to use before the database has been migrated: every
| settings lookup is wrapped in a try/catch inside the Setting model.
|
*/

if (! function_exists('setting')) {
    /**
     * Read one value from the `settings` table (cached per request).
     */
    function setting(string $key, $default = null)
    {
        return Setting::value($key, $default);
    }
}

if (! function_exists('site_name')) {
    function site_name(): string
    {
        return (string) (setting('site_name') ?: 'DheyaDev');
    }
}

if (! function_exists('site_description')) {
    function site_description(): string
    {
        $value = is_rtl()
            ? setting('site_description_ar')
            : setting('site_description');

        return (string) ($value ?: setting('site_description') ?: '');
    }
}

if (! function_exists('author_name')) {
    function author_name(): string
    {
        return is_rtl()
            ? (string) (setting('author_name_ar') ?: 'ضياء عباس')
            : (string) (setting('author_name') ?: 'Dheya Abbas');
    }
}

if (! function_exists('author_title')) {
    function author_title(): string
    {
        return is_rtl()
            ? (string) (setting('author_title_ar') ?: 'مهندس ومطور برمجيات')
            : (string) (setting('author_title') ?: 'Software Engineer & Developer');
    }
}

if (! function_exists('author_bio')) {
    function author_bio(): string
    {
        return is_rtl()
            ? (string) (setting('author_bio_ar') ?: __('site.footer_bio'))
            : (string) (setting('author_bio') ?: __('site.footer_bio'));
    }
}

if (! function_exists('is_rtl')) {
    /**
     * Current interface direction (Arabic interface uses RTL).
     */
    function is_rtl(): bool
    {
        return App::getLocale() === 'ar';
    }
}

if (! function_exists('lang_q')) {
    /**
     * Language query string used by internal links, mirroring the SSR engine.
     * English pages keep ?lang=en, Arabic pages stay on the clean URL.
     */
    function lang_q(): string
    {
        return App::getLocale() === 'en' ? '?lang=en' : '';
    }
}

if (! function_exists('loc_field')) {
    /**
     * Return the localized flavour of a model/array field:
     * `title_ar` while browsing in Arabic (when filled), otherwise `title`.
     */
    function loc_field($model, string $field, $default = '')
    {
        $primary = data_get($model, $field);
        $value = $primary;

        if (is_rtl()) {
            $arabic = data_get($model, $field.'_ar');
            $value = filled($arabic) ? $arabic : $primary;
        }

        if (! filled($value)) {
            return $default;
        }

        return $value;
    }
}

if (! function_exists('url_path')) {
    /**
     * Build a public URL from a stored path such as "/images/og-cover.png".
     */
    function url_path($path, $fallback = null): string
    {
        $path = $path ?: $fallback;

        if (! $path) {
            return '';
        }

        if (str_starts_with((string) $path, 'http')) {
            return (string) $path;
        }

        return url('/'.ltrim((string) $path, '/'));
    }
}

if (! function_exists('absolute_url')) {
    /**
     * Absolute (canonical/OG) URL for a stored media path.
     */
    function absolute_url($path, $fallback = null): string
    {
        return url_path($path, $fallback);
    }
}

if (! function_exists('current_url_without_query')) {
    function current_url_without_query(): string
    {
        return url()->current();
    }
}

if (! function_exists('markdown_html')) {
    /**
     * Render an article body: Markdown -> HTML with heading anchors,
     * a copy-button code wrapper and a table of contents.
     */
    function markdown_html(?string $markdown): string
    {
        return Markdown::toHtml($markdown);
    }
}

if (! function_exists('markdown_toc')) {
    /**
     * @return array<int, array{level:int, text:string, id:string}>
     */
    function markdown_toc(?string $markdown): array
    {
        return Markdown::toc($markdown);
    }
}

if (! function_exists('post_body')) {
    /**
     * Raw (localized) Markdown body of a post.
     */
    function post_body($post): string
    {
        $arabic = data_get($post, 'content_ar');
        $english = data_get($post, 'content');

        if (is_rtl() && filled($arabic)) {
            return (string) $arabic;
        }

        return (string) ($english ?: $arabic);
    }
}

if (! function_exists('schema_json')) {
    /**
     * Encode a schema.org structure for a JSON-LD script tag.
     */
    function schema_json(array $schema): string
    {
        return (string) json_encode(
            $schema,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        );
    }
}

if (! function_exists('person_schema')) {
    function person_schema(): array
    {
        $base = url('/');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => setting('author_name') ?: 'Dheya Abbas',
            'alternateName' => setting('author_name_ar') ?: 'ضياء عباس',
            'jobTitle' => setting('author_title') ?: 'Software Engineer & Developer',
            'url' => $base,
            'email' => setting('email') ?: 'hello@dheyadev.com',
            'image' => absolute_url(setting('profile_image'), '/images/dheya-profile.jpg'),
            'sameAs' => array_values(array_filter([
                setting('github'),
                setting('telegram'),
                setting('linkedin'),
            ])),
        ];
    }
}

if (! function_exists('article_schema')) {
    function article_schema($post): array
    {
        $base = url('/');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => (string) data_get($post, 'title'),
            'description' => (string) (data_get($post, 'excerpt') ?: data_get($post, 'meta_description')),
            'image' => absolute_url(data_get($post, 'featured_image'), '/images/og-cover.png'),
            'datePublished' => optional(data_get($post, 'published_at'))->toAtomString(),
            'dateModified' => optional(data_get($post, 'updated_at') ?: data_get($post, 'published_at'))->toAtomString(),
            'author' => [
                '@type' => 'Person',
                'name' => setting('author_name') ?: 'Dheya Abbas',
                'url' => $base,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => site_name(),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $base.'/favicon.svg',
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $base.'/blog/'.data_get($post, 'slug'),
            ],
        ];
    }
}

if (! function_exists('breadcrumb_schema')) {
    /**
     * @param  array<int, array{name:string, url:string}>  $items
     */
    function breadcrumb_schema(array $items): array
    {
        $base = url('/');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_values(array_map(function ($item, $index) use ($base) {
                $url = $item['url'] ?? '';

                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'] ?? '',
                    'item' => str_starts_with($url, 'http') ? $url : $base.'/'.ltrim($url, '/'),
                ];
            }, $items, array_keys($items))),
        ];
    }
}

if (! function_exists('public_asset')) {
    /**
     * Asset URL with a cache-busting query string based on file mtime.
     */
    function public_asset(string $path): string
    {
        $path = ltrim($path, '/');
        $full = public_path($path);
        $version = is_file($full) ? filemtime($full) : null;

        return $version ? url($path).'?v='.$version : url($path);
    }
}

if (! function_exists('jdate')) {
    /**
     * Human friendly publication date, localized.
     */
    function jdate($date): string
    {
        if (! $date) {
            return '';
        }

        return is_rtl()
            ? $date->translatedFormat('j F Y')
            : $date->format('M j, Y');
    }
}

if (! function_exists('wants_json')) {
    function wants_json(): bool
    {
        return Request::expectsJson();
    }
}
