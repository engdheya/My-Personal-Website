const { marked } = require('marked');
const hljs = require('highlight.js');
const sanitizeHtml = require('sanitize-html');
const db = require('./db');

// Custom Markdown renderer for headings and code blocks
const renderer = new marked.Renderer();

renderer.heading = function ({ text, depth }) {
  // Generate clean slug ID from heading text
  const cleanId = text
    .toLowerCase()
    .replace(/<[^>]+>/g, '')
    .replace(/[^\w\u0600-\u06FF]+/g, '-')
    .replace(/^-+|-+$/g, '');

  return `<h${depth} id="${cleanId}" class="scroll-mt-20 group relative font-bold text-slate-900 dark:text-white ${
    depth === 2 ? 'text-2xl mt-8 mb-4' : 'text-xl mt-6 mb-3'
  }">
    <a href="#${cleanId}" class="text-inherit hover:underline">${text}</a>
  </h${depth}>`;
};

renderer.code = function ({ text, lang }) {
  const validLang = lang && hljs.getLanguage(lang) ? lang : 'plaintext';
  let highlighted = '';
  try {
    highlighted = hljs.highlight(text, { language: validLang }).value;
  } catch (e) {
    highlighted = hljs.highlightAuto(text).value;
  }

  // Code wrapper forced to LTR with copy button
  return `
<div class="code-wrapper not-prose my-6 rounded-lg overflow-hidden border border-slate-700 bg-slate-950 text-slate-100 shadow-md" dir="ltr">
  <div class="code-header flex items-center justify-between px-4 py-2 bg-slate-900 border-b border-slate-800 text-xs text-slate-400 font-mono">
    <span>${validLang.toUpperCase()}</span>
    <button type="button" onclick="copyCode(this)" class="copy-btn px-2 py-1 rounded bg-slate-800 hover:bg-slate-700 text-slate-300 transition-colors text-xs flex items-center gap-1 cursor-pointer">
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
      <span>Copy</span>
    </button>
  </div>
  <pre class="p-4 overflow-x-auto text-sm font-mono leading-relaxed"><code class="hljs language-${validLang}">${highlighted}</code></pre>
</div>`;
};

marked.setOptions({
  renderer: renderer,
  gfm: true,
  breaks: true,
});

function renderMarkdown(content) {
  if (!content) return '';
  return marked.parse(content);
}

// Extract Table of Contents from content (H2, H3)
function extractTOC(content) {
  if (!content) return [];
  const headingRegex = /^(#{2,3})\s+(.+)$/gm;
  const toc = [];
  let match;

  while ((match = headingRegex.exec(content)) !== null) {
    const level = match[1].length;
    const text = match[2].trim();
    const id = text
      .toLowerCase()
      .replace(/[^\w\u0600-\u06FF]+/g, '-')
      .replace(/^-+|-+$/g, '');

    toc.push({ level, text, id });
  }

  return toc;
}

// Calculate reading time (~200 words per minute)
function calculateReadingTime(text) {
  if (!text) return 1;
  const wordCount = text.trim().split(/\s+/).length;
  return Math.max(1, Math.ceil(wordCount / 200));
}

// Slugify text
function slugify(text) {
  return text
    .toString()
    .toLowerCase()
    .trim()
    .replace(/[^\w\u0600-\u06FF\s-]/g, '')
    .replace(/[\s_-]+/g, '-')
    .replace(/^-+|-+$/g, '');
}

// Cache settings in memory with 5-minute TTL or immediate refresh
let cachedSettings = null;
let settingsCacheTime = 0;

function getSettings() {
  const now = Date.now();
  if (cachedSettings && now - settingsCacheTime < 300000) {
    return cachedSettings;
  }
  const rows = db.prepare('SELECT key, value FROM settings').all();
  const settings = {};
  for (const row of rows) {
    settings[row.key] = row.value;
  }
  cachedSettings = settings;
  settingsCacheTime = now;
  return settings;
}

function clearSettingsCache() {
  cachedSettings = null;
}

// Schema.org Generators
function getPersonSchema(settings, baseUrl) {
  return JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'Person',
    name: settings.author_name || 'Dheya Abbas',
    alternateName: settings.author_name_ar || 'ضياء عباس',
    jobTitle: settings.author_title || 'Software Engineer & Developer',
    url: baseUrl,
    email: settings.email || 'hello@dheyadev.com',
    image: `${baseUrl}${settings.profile_image || '/images/dheya-profile.jpg'}`,
    sameAs: [
      settings.github || 'https://github.com/engdheya',
      settings.telegram || 'https://t.me/engdheya',
      settings.linkedin || 'https://linkedin.com/in/engdheya',
    ].filter(Boolean),
  });
}

function getArticleSchema(post, authorName, baseUrl) {
  return JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'BlogPosting',
    headline: post.title,
    description: post.excerpt || post.meta_description,
    image: post.featured_image ? `${baseUrl}${post.featured_image}` : `${baseUrl}/images/og-cover.png`,
    datePublished: post.published_at,
    dateModified: post.updated_at || post.published_at,
    author: {
      '@type': 'Person',
      name: authorName || 'Dheya Abbas',
      url: baseUrl,
    },
    publisher: {
      '@type': 'Organization',
      name: 'DheyaDev',
      logo: {
        '@type': 'ImageObject',
        url: `${baseUrl}/favicon.svg`,
      },
    },
    mainEntityOfPage: {
      '@type': 'WebPage',
      '@id': `${baseUrl}/blog/${post.slug}`,
    },
  });
}

function getBreadcrumbSchema(items, baseUrl) {
  return JSON.stringify({
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    itemListElement: items.map((item, idx) => ({
      '@type': 'ListItem',
      position: idx + 1,
      name: item.name,
      item: item.url.startsWith('http') ? item.url : `${baseUrl}${item.url}`,
    })),
  });
}

module.exports = {
  renderMarkdown,
  extractTOC,
  calculateReadingTime,
  slugify,
  getSettings,
  clearSettingsCache,
  getPersonSchema,
  getArticleSchema,
  getBreadcrumbSchema,
};
