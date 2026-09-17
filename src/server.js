require('dotenv').config();
const path = require('path');
const fs = require('fs');
const express = require('express');
const session = require('express-session');
const cookieParser = require('cookie-parser');
const helmet = require('helmet');
const bcrypt = require('bcryptjs');

const db = require('./db');
const seedDatabase = require('./seed');
const en = require('./lang/en');
const ar = require('./lang/ar');
const {
  renderMarkdown,
  extractTOC,
  calculateReadingTime,
  slugify,
  getSettings,
  clearSettingsCache,
  getPersonSchema,
  getArticleSchema,
  getBreadcrumbSchema,
} = require('./helpers');

// Initialize database schema & seed if needed
seedDatabase();

const app = express();
const PORT = process.env.PORT || 8000;

// View engine setup
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

// Security & Parsing Middleware
app.use(helmet({
  contentSecurityPolicy: false, // Disabled to allow inline scripts for instant theme switching & previews
}));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));
app.use(express.json({ limit: '10mb' }));
app.use(cookieParser());
app.use(express.static(path.join(__dirname, '../public')));

// Session middleware
app.use(session({
  secret: process.env.SESSION_SECRET || 'dheyadev-secret-key-2026-production',
  resave: false,
  saveUninitialized: false,
  cookie: {
    httpOnly: true,
    maxAge: 7 * 24 * 60 * 60 * 1000, // 7 days
    sameSite: 'lax',
  },
}));

// Language & Theme & Settings Middleware
app.use((req, res, next) => {
  // Determine Language: Query param > Cookie > Default (ar)
  let lang = req.query.lang;
  if (lang === 'ar' || lang === 'en') {
    res.cookie('dheyadev_lang', lang, { maxAge: 365 * 24 * 60 * 60 * 1000, httpOnly: false });
  } else {
    lang = req.cookies.dheyadev_lang || 'ar';
  }
  req.lang = lang;
  res.locals.currentLang = lang;
  res.locals.t = lang === 'ar' ? ar : en;

  // Flash messages from session
  res.locals.flashSuccess = req.session.flashSuccess || null;
  res.locals.flashError = req.session.flashError || null;
  req.session.flashSuccess = null;
  req.session.flashError = null;

  // Global settings
  const settings = getSettings();
  res.locals.settings = settings;

  // Host baseUrl for SEO
  const protocol = req.headers['x-forwarded-proto'] || req.protocol;
  const host = req.get('host');
  res.locals.baseUrl = `${protocol}://${host}`;

  // Default SEO
  res.locals.seo = {
    title: settings.default_seo_title || `${settings.site_name || 'DheyaDev'} | ${settings.site_tagline || 'Software Engineer'}`,
    description: settings.default_meta_description || settings.site_description,
    canonical: `${res.locals.baseUrl}${req.path}`,
    ogImage: `${res.locals.baseUrl}${settings.default_og_image || '/images/og-cover.png'}`,
    ogType: 'website',
  };

  // Helper for rendering with layout
  res.renderWithLayout = (view, data = {}, layout = 'layouts/app') => {
    const mergedData = { ...res.locals, ...data };
    app.render(view, mergedData, (err, html) => {
      if (err) {
        console.error('Error rendering inner view:', err);
        return res.status(500).send('View Render Error: ' + err.message);
      }
      mergedData.body = html;
      app.render(layout, mergedData, (layoutErr, fullHtml) => {
        if (layoutErr) {
          console.error('Error rendering layout:', layoutErr);
          return res.status(500).send('Layout Render Error: ' + layoutErr.message);
        }
        res.send(fullHtml);
      });
    });
  };

  next();
});

// Admin Auth Middleware
function requireAdmin(req, res, next) {
  if (!req.session.userId) {
    return res.redirect('/admin/login');
  }
  const user = db.prepare('SELECT id, name, email, role FROM users WHERE id = ?').get(req.session.userId);
  if (!user) {
    req.session.destroy();
    return res.redirect('/admin/login');
  }
  req.user = user;
  res.locals.user = user;
  
  // Unread messages count
  const unread = db.prepare("SELECT COUNT(*) as count FROM messages WHERE status = 'unread'").get();
  res.locals.unreadMessagesCount = unread ? unread.count : 0;

  next();
}

// ==========================================
// PUBLIC ROUTES
// ==========================================

// 1. Home Page
app.get('/', (req, res) => {
  const featuredProjects = db.prepare(`
    SELECT * FROM projects 
    WHERE is_featured = 1 
    ORDER BY id DESC 
    LIMIT 3
  `).all();

  const latestPosts = db.prepare(`
    SELECT posts.*, categories.name as category_name, categories.name_ar as category_name_ar, categories.slug as category_slug
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE posts.status = 'published' AND posts.published_at <= CURRENT_TIMESTAMP
    ORDER BY posts.published_at DESC 
    LIMIT 3
  `).all();

  const services = db.prepare('SELECT * FROM services ORDER BY sort_order ASC, id ASC LIMIT 4').all();

  // Person Schema
  const schemaHtml = `<script type="application/ld+json">${getPersonSchema(res.locals.settings, res.locals.baseUrl)}</script>`;

  res.renderWithLayout('home', {
    activePage: 'home',
    featuredProjects,
    latestPosts,
    services,
    schemaHtml,
    seo: {
      ...res.locals.seo,
      title: res.locals.currentLang === 'ar' 
        ? `${res.locals.settings.author_name_ar || 'ضياء عباس'} | ${res.locals.settings.author_title_ar || 'مهندس برمجيات'}`
        : `${res.locals.settings.author_name || 'Dheya Abbas'} | ${res.locals.settings.author_title || 'Software Engineer & Developer'}`,
    }
  });
});

// 2. About Page
app.get('/about', (req, res) => {
  const breadcrumbs = [
    { name: res.locals.t.nav_about, url: '/about' },
  ];
  const schemaHtml = `
    <script type="application/ld+json">${getPersonSchema(res.locals.settings, res.locals.baseUrl)}</script>
    <script type="application/ld+json">${getBreadcrumbSchema(breadcrumbs, res.locals.baseUrl)}</script>
  `;

  res.renderWithLayout('about', {
    activePage: 'about',
    breadcrumbs,
    schemaHtml,
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.nav_about} | ${res.locals.settings.site_name || 'DheyaDev'}`,
      description: res.locals.currentLang === 'ar' ? res.locals.settings.author_bio_ar : res.locals.settings.author_bio,
    }
  });
});

// 3. Projects Page
app.get('/projects', (req, res) => {
  const { tech } = req.query;
  let projects;
  if (tech) {
    projects = db.prepare('SELECT * FROM projects WHERE technologies LIKE ? ORDER BY id DESC').all(`%${tech}%`);
  } else {
    projects = db.prepare('SELECT * FROM projects ORDER BY is_featured DESC, id DESC').all();
  }

  const breadcrumbs = [
    { name: res.locals.t.nav_projects, url: '/projects' },
  ];

  res.renderWithLayout('projects/index', {
    activePage: 'projects',
    projects,
    selectedFilter: tech || null,
    breadcrumbs,
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.nav_projects} | ${res.locals.settings.site_name || 'DheyaDev'}`,
      description: 'Explore software projects, mobile apps, and systems built with Laravel, Flutter, and MySQL by Dheya Abbas.',
    }
  });
});

// 4. Project Detail Page
app.get('/projects/:slug', (req, res, next) => {
  const project = db.prepare('SELECT * FROM projects WHERE slug = ?').get(req.params.slug);
  if (!project) return next();

  const relatedProjects = db.prepare('SELECT * FROM projects WHERE id != ? ORDER BY id DESC LIMIT 2').all(project.id);

  const breadcrumbs = [
    { name: res.locals.t.nav_projects, url: '/projects' },
    { name: res.locals.currentLang === 'ar' ? (project.title_ar || project.title) : project.title, url: `/projects/${project.slug}` },
  ];

  const schemaHtml = `<script type="application/ld+json">${getBreadcrumbSchema(breadcrumbs, res.locals.baseUrl)}</script>`;

  res.renderWithLayout('projects/show', {
    activePage: 'projects',
    project,
    relatedProjects,
    breadcrumbs,
    schemaHtml,
    seo: {
      title: project.seo_title || `${project.title} | ${res.locals.settings.site_name}`,
      description: project.meta_description || project.short_description,
      canonical: `${res.locals.baseUrl}/projects/${project.slug}`,
      ogImage: project.main_image ? `${res.locals.baseUrl}${project.main_image}` : res.locals.seo.ogImage,
      ogType: 'article',
    }
  });
});

// 5. Blog Listing
app.get('/blog', (req, res) => {
  const page = parseInt(req.query.page) || 1;
  const limit = 12; // 12 articles per page (Requirement 58)
  const offset = (page - 1) * limit;
  const categorySlug = req.query.category;

  let postsQuery = `
    SELECT posts.*, categories.name as category_name, categories.name_ar as category_name_ar, categories.slug as category_slug
    FROM posts
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE posts.status = 'published' AND posts.published_at <= CURRENT_TIMESTAMP
  `;
  let countQuery = `
    SELECT COUNT(*) as total FROM posts
    WHERE status = 'published' AND published_at <= CURRENT_TIMESTAMP
  `;
  const params = [];

  let currentCategory = null;
  if (categorySlug) {
    currentCategory = db.prepare('SELECT * FROM categories WHERE slug = ?').get(categorySlug);
    if (currentCategory) {
      postsQuery += ' AND posts.category_id = ?';
      countQuery += ' AND category_id = ?';
      params.push(currentCategory.id);
    }
  }

  postsQuery += ' ORDER BY posts.published_at DESC LIMIT ? OFFSET ?';
  const totalRows = db.prepare(countQuery).get(...params).total;
  const totalPages = Math.ceil(totalRows / limit) || 1;

  const posts = db.prepare(postsQuery).all(...params, limit, offset);

  // All categories with post counts
  const categories = db.prepare(`
    SELECT categories.*, COUNT(posts.id) as posts_count 
    FROM categories 
    LEFT JOIN posts ON categories.id = posts.category_id AND posts.status = 'published'
    GROUP BY categories.id
  `).all();

  // All tags
  const tags = db.prepare('SELECT * FROM tags ORDER BY name ASC').all();

  const breadcrumbs = [
    { name: res.locals.t.nav_blog, url: '/blog' },
  ];
  if (currentCategory) {
    breadcrumbs.push({
      name: res.locals.currentLang === 'ar' ? (currentCategory.name_ar || currentCategory.name) : currentCategory.name,
      url: `/blog?category=${currentCategory.slug}`,
    });
  }

  res.renderWithLayout('blog/index', {
    activePage: 'blog',
    posts,
    categories,
    tags,
    currentCategory,
    selectedCategorySlug: categorySlug || null,
    currentPage: page,
    totalPages,
    breadcrumbs,
    seo: {
      ...res.locals.seo,
      title: currentCategory 
        ? `${currentCategory.seo_title || currentCategory.name} | ${res.locals.settings.site_name}`
        : `${res.locals.t.all_articles_title} | ${res.locals.settings.site_name}`,
      description: currentCategory ? currentCategory.meta_description : res.locals.seo.description,
    }
  });
});

// 6. Single Article Page
app.get('/blog/:slug', (req, res, next) => {
  const post = db.prepare(`
    SELECT posts.*, categories.name as category_name, categories.name_ar as category_name_ar, categories.slug as category_slug
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE posts.slug = ?
  `).get(req.params.slug);

  if (!post) return next();

  // If unpublished, require admin login to preview
  if (post.status !== 'published' || new Date(post.published_at) > new Date()) {
    if (!req.session.userId) {
      return res.status(404).renderWithLayout('errors/404', { activePage: '' });
    }
  }

  // Increment view counter
  db.prepare('UPDATE posts SET views_count = views_count + 1 WHERE id = ?').run(post.id);

  // Select language content
  const isArabic = res.locals.currentLang === 'ar';
  const rawContent = (isArabic && post.content_ar) ? post.content_ar : post.content;
  
  // Render Markdown + Highlight.js code blocks
  const contentHtml = renderMarkdown(rawContent);

  // Extract Table of Contents
  const toc = extractTOC(rawContent);

  // Tags for this post
  const postTags = db.prepare(`
    SELECT tags.* FROM tags 
    JOIN post_tag ON tags.id = post_tag.tag_id
    WHERE post_tag.post_id = ?
  `).all(post.id);

  // Related posts in same category
  const relatedPosts = db.prepare(`
    SELECT posts.*, categories.name as category_name 
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE posts.category_id = ? AND posts.id != ? AND posts.status = 'published'
    ORDER BY posts.published_at DESC LIMIT 2
  `).all(post.category_id, post.id);

  const breadcrumbs = [
    { name: res.locals.t.nav_blog, url: '/blog' },
    { name: isArabic ? (post.category_name_ar || post.category_name) : post.category_name, url: `/blog?category=${post.category_slug}` },
    { name: isArabic ? (post.title_ar || post.title) : post.title, url: `/blog/${post.slug}` },
  ];

  const schemaHtml = `
    <script type="application/ld+json">${getArticleSchema(post, res.locals.settings.author_name, res.locals.baseUrl)}</script>
    <script type="application/ld+json">${getBreadcrumbSchema(breadcrumbs, res.locals.baseUrl)}</script>
  `;

  res.renderWithLayout('blog/show', {
    activePage: 'blog',
    post,
    contentHtml,
    toc,
    postTags,
    relatedPosts,
    breadcrumbs,
    schemaHtml,
    seo: {
      title: post.seo_title || `${post.title} | ${res.locals.settings.site_name}`,
      description: post.meta_description || post.excerpt,
      canonical: post.canonical_url || `${res.locals.baseUrl}/blog/${post.slug}`,
      ogImage: post.og_image || (post.featured_image ? `${res.locals.baseUrl}${post.featured_image}` : res.locals.seo.ogImage),
      ogType: 'article',
      keywords: post.focus_keyword || '',
    }
  });
});

// 7. Services Page
app.get('/services', (req, res) => {
  const services = db.prepare('SELECT * FROM services ORDER BY sort_order ASC, id ASC').all();
  const breadcrumbs = [
    { name: res.locals.t.nav_services, url: '/services' },
  ];

  res.renderWithLayout('services', {
    activePage: 'services',
    services,
    breadcrumbs,
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.nav_services} | ${res.locals.settings.site_name || 'DheyaDev'}`,
      description: 'Professional software engineering services: Laravel backend development, Flutter mobile apps, RESTful API design, and MySQL database optimization.',
    }
  });
});

// 8. Contact Page
app.get('/contact', (req, res) => {
  const breadcrumbs = [
    { name: res.locals.t.nav_contact, url: '/contact' },
  ];

  res.renderWithLayout('contact', {
    activePage: 'contact',
    prefillSubject: req.query.service ? `Inquiry regarding: ${req.query.service}` : '',
    breadcrumbs,
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.nav_contact} | ${res.locals.settings.site_name || 'DheyaDev'}`,
      description: 'Get in touch with Dheya Abbas for software engineering consultations, custom web & mobile development, and architecture reviews.',
    }
  });
});

app.post('/contact', (req, res) => {
  const { name, email, subject, message, _gotcha } = req.body;

  // Honeypot spam trap (Requirement 22, 48)
  if (_gotcha) {
    console.warn('Spam submission prevented via honeypot.');
    req.session.flashSuccess = res.locals.t.contact_success;
    return res.redirect('/contact');
  }

  // Validation
  if (!name || !email || !subject || !message) {
    req.session.flashError = 'Please fill out all required fields.';
    return res.redirect('/contact');
  }

  // Save to database (Requirement 22, 84)
  const clientIp = req.headers['x-forwarded-for'] || req.socket.remoteAddress;
  db.prepare(`
    INSERT INTO messages (name, email, subject, message, status, ip_address)
    VALUES (?, ?, ?, ?, 'unread', ?)
  `).run(name.trim(), email.trim(), subject.trim(), message.trim(), clientIp);

  console.log(`[Contact] New message received from ${name} <${email}>: ${subject}`);

  req.session.flashSuccess = res.locals.t.contact_success;
  res.redirect('/contact');
});

// 9. Search Page
app.get('/search', (req, res) => {
  const q = req.query.q ? req.query.q.trim() : '';
  let matchingPosts = [];
  let matchingProjects = [];

  if (q) {
    const term = `%${q}%`;
    matchingPosts = db.prepare(`
      SELECT posts.*, categories.name as category_name 
      FROM posts 
      LEFT JOIN categories ON posts.category_id = categories.id
      WHERE posts.status = 'published' AND (posts.title LIKE ? OR posts.title_ar LIKE ? OR posts.content LIKE ? OR posts.content_ar LIKE ?)
      ORDER BY posts.published_at DESC LIMIT 20
    `).all(term, term, term, term);

    matchingProjects = db.prepare(`
      SELECT * FROM projects 
      WHERE title LIKE ? OR title_ar LIKE ? OR description LIKE ? OR technologies LIKE ?
      ORDER BY id DESC LIMIT 10
    `).all(term, term, term, term);
  }

  const breadcrumbs = [
    { name: res.locals.t.search_title, url: '/search' },
  ];

  res.renderWithLayout('search', {
    activePage: 'search',
    query: q,
    matchingPosts,
    matchingProjects,
    breadcrumbs,
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.search_title}: ${q} | ${res.locals.settings.site_name}`,
      noindex: true, // Do not index search query pages (prevent duplicate content)
    }
  });
});

// 10. Legal Pages
app.get('/privacy-policy', (req, res) => {
  res.renderWithLayout('privacy', {
    activePage: '',
    breadcrumbs: [{ name: res.locals.t.privacy_title, url: '/privacy-policy' }],
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.privacy_title} | ${res.locals.settings.site_name}`,
    }
  });
});

app.get('/terms', (req, res) => {
  res.renderWithLayout('terms', {
    activePage: '',
    breadcrumbs: [{ name: res.locals.t.terms_title, url: '/terms' }],
    seo: {
      ...res.locals.seo,
      title: `${res.locals.t.terms_title} | ${res.locals.settings.site_name}`,
    }
  });
});

// 11. XML Sitemap Generator (Requirement 34)
app.get('/sitemap.xml', (req, res) => {
  const baseUrl = 'https://dheyadev.com';
  const posts = db.prepare("SELECT slug, updated_at, published_at FROM posts WHERE status = 'published' AND published_at <= CURRENT_TIMESTAMP").all();
  const projects = db.prepare('SELECT slug, updated_at FROM projects').all();
  const categories = db.prepare('SELECT slug, updated_at FROM categories').all();

  let xml = '<?xml version="1.0" encoding="UTF-8"?>\n';
  xml += '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n';

  // Static pages
  const staticRoutes = [
    { url: '/', priority: '1.0', changefreq: 'daily' },
    { url: '/about', priority: '0.8', changefreq: 'weekly' },
    { url: '/projects', priority: '0.9', changefreq: 'weekly' },
    { url: '/blog', priority: '0.9', changefreq: 'daily' },
    { url: '/services', priority: '0.8', changefreq: 'weekly' },
    { url: '/contact', priority: '0.7', changefreq: 'monthly' },
    { url: '/privacy-policy', priority: '0.3', changefreq: 'yearly' },
    { url: '/terms', priority: '0.3', changefreq: 'yearly' },
  ];

  const now = new Date().toISOString().split('T')[0];

  staticRoutes.forEach(r => {
    xml += `  <url>\n    <loc>${baseUrl}${r.url}</loc>\n    <lastmod>${now}</lastmod>\n    <changefreq>${r.changefreq}</changefreq>\n    <priority>${r.priority}</priority>\n  </url>\n`;
  });

  // Blog Posts
  posts.forEach(p => {
    const mod = (p.updated_at || p.published_at || now).split(' ')[0];
    xml += `  <url>\n    <loc>${baseUrl}/blog/${p.slug}</loc>\n    <lastmod>${mod}</lastmod>\n    <changefreq>weekly</changefreq>\n    <priority>0.8</priority>\n  </url>\n`;
  });

  // Projects
  projects.forEach(p => {
    const mod = (p.updated_at || now).split(' ')[0];
    xml += `  <url>\n    <loc>${baseUrl}/projects/${p.slug}</loc>\n    <lastmod>${mod}</lastmod>\n    <changefreq>monthly</changefreq>\n    <priority>0.7</priority>\n  </url>\n`;
  });

  // Categories
  categories.forEach(c => {
    const mod = (c.updated_at || now).split(' ')[0];
    xml += `  <url>\n    <loc>${baseUrl}/blog?category=${c.slug}</loc>\n    <lastmod>${mod}</lastmod>\n    <changefreq>weekly</changefreq>\n    <priority>0.6</priority>\n  </url>\n`;
  });

  xml += '</urlset>';

  res.header('Content-Type', 'application/xml');
  res.send(xml);
});

// 12. Robots.txt (Requirement 35)
app.get('/robots.txt', (req, res) => {
  const robots = `User-agent: *
Disallow: /admin
Disallow: /admin/
Allow: /

Sitemap: https://dheyadev.com/sitemap.xml
`;
  res.header('Content-Type', 'text/plain');
  res.send(robots);
});

// ==========================================
// ADMIN PANEL ROUTES (Filament Style)
// ==========================================

// Login GET
app.get('/admin/login', (req, res) => {
  if (req.session.userId) return res.redirect('/admin');
  res.render('admin/login', { error: null });
});

// Login POST
app.post('/admin/login', (req, res) => {
  const { email, password } = req.body;
  const user = db.prepare('SELECT * FROM users WHERE email = ?').get(email);

  if (!user || !bcrypt.compareSync(password, user.password)) {
    return res.render('admin/login', { error: 'Invalid email or password credentials.' });
  }

  req.session.userId = user.id;
  req.session.flashSuccess = 'Logged in successfully.';
  res.redirect('/admin');
});

// Logout
app.get('/admin/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/admin/login');
});

// Admin Dashboard (Requirement 25, 94)
app.get('/admin', requireAdmin, (req, res) => {
  const totalPosts = db.prepare('SELECT COUNT(*) as count FROM posts').get().count;
  const publishedPosts = db.prepare("SELECT COUNT(*) as count FROM posts WHERE status = 'published'").get().count;
  const draftPosts = db.prepare("SELECT COUNT(*) as count FROM posts WHERE status = 'draft'").get().count;
  const totalProjects = db.prepare('SELECT COUNT(*) as count FROM projects').get().count;
  const totalMessages = db.prepare('SELECT COUNT(*) as count FROM messages').get().count;
  const unreadMessages = db.prepare("SELECT COUNT(*) as count FROM messages WHERE status = 'unread'").get().count;
  const totalCategories = db.prepare('SELECT COUNT(*) as count FROM categories').get().count;

  const recentPosts = db.prepare(`
    SELECT posts.*, categories.name as category_name 
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id 
    ORDER BY posts.id DESC LIMIT 5
  `).all();

  const recentMessages = db.prepare('SELECT * FROM messages ORDER BY id DESC LIMIT 5').all();

  res.renderWithLayout('admin/dashboard', {
    activeAdminNav: 'dashboard',
    pageTitle: 'Dashboard',
    stats: {
      totalPosts,
      publishedPosts,
      draftPosts,
      totalProjects,
      totalMessages,
      unreadMessages,
      totalCategories,
    },
    recentPosts,
    recentMessages,
  }, 'layouts/admin');
});

// Admin Posts: List
app.get('/admin/posts', requireAdmin, (req, res) => {
  const { status, search } = req.query;
  let query = `
    SELECT posts.*, categories.name as category_name 
    FROM posts 
    LEFT JOIN categories ON posts.category_id = categories.id
    WHERE 1=1
  `;
  const params = [];

  if (status) {
    query += ' AND posts.status = ?';
    params.push(status);
  }
  if (search) {
    query += ' AND posts.title LIKE ?';
    params.push(`%${search}%`);
  }

  query += ' ORDER BY posts.id DESC';
  const posts = db.prepare(query).all(...params);

  res.renderWithLayout('admin/posts/index', {
    activeAdminNav: 'posts',
    pageTitle: 'Articles',
    posts,
    selectedStatus: status || null,
    search: search || null,
  }, 'layouts/admin');
});

// Admin Posts: Create Form
app.get('/admin/posts/new', requireAdmin, (req, res) => {
  const categories = db.prepare('SELECT * FROM categories ORDER BY name ASC').all();
  res.renderWithLayout('admin/posts/form', {
    activeAdminNav: 'posts',
    pageTitle: 'New Article',
    isEdit: false,
    post: {},
    categories,
  }, 'layouts/admin');
});

// Admin Posts: Create Action
app.post('/admin/posts', requireAdmin, (req, res) => {
  const {
    title, title_ar, slug, category_id, status, featured_image,
    excerpt, content, seo_title, meta_description, focus_keyword, canonical_url
  } = req.body;

  const cleanSlug = slugify(slug || title);
  const readingTime = calculateReadingTime(content);

  try {
    db.prepare(`
      INSERT INTO posts (
        user_id, category_id, title, title_ar, slug, excerpt, excerpt_ar, content, content_ar,
        featured_image, status, reading_time, seo_title, meta_description, focus_keyword, canonical_url, published_at
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)
    `).run(
      req.user.id, category_id, title.trim(), title_ar ? title_ar.trim() : title.trim(),
      cleanSlug, excerpt, excerpt, content, content, featured_image || '/images/posts/laravel-mysql-driver.jpg',
      status || 'published', readingTime, seo_title || title, meta_description || excerpt,
      focus_keyword, canonical_url
    );

    req.session.flashSuccess = 'Article created and saved successfully!';
    res.redirect('/admin/posts');
  } catch (err) {
    console.error('Error creating post:', err);
    req.session.flashError = 'Error: ' + err.message;
    res.redirect('/admin/posts/new');
  }
});

// Admin Posts: Edit Form
app.get('/admin/posts/:id/edit', requireAdmin, (req, res) => {
  const post = db.prepare('SELECT * FROM posts WHERE id = ?').get(req.params.id);
  if (!post) return res.redirect('/admin/posts');

  const categories = db.prepare('SELECT * FROM categories ORDER BY name ASC').all();

  res.renderWithLayout('admin/posts/form', {
    activeAdminNav: 'posts',
    pageTitle: `Edit: ${post.title}`,
    isEdit: true,
    post,
    categories,
  }, 'layouts/admin');
});

// Admin Posts: Update Action
app.post('/admin/posts/:id', requireAdmin, (req, res) => {
  const {
    title, title_ar, slug, category_id, status, featured_image,
    excerpt, content, seo_title, meta_description, focus_keyword, canonical_url
  } = req.body;

  const cleanSlug = slugify(slug || title);
  const readingTime = calculateReadingTime(content);

  try {
    db.prepare(`
      UPDATE posts SET
        category_id = ?, title = ?, title_ar = ?, slug = ?, excerpt = ?, content = ?,
        featured_image = ?, status = ?, reading_time = ?, seo_title = ?, meta_description = ?,
        focus_keyword = ?, canonical_url = ?, updated_at = CURRENT_TIMESTAMP
      WHERE id = ?
    `).run(
      category_id, title.trim(), title_ar ? title_ar.trim() : title.trim(),
      cleanSlug, excerpt, content, featured_image, status, readingTime,
      seo_title, meta_description, focus_keyword, canonical_url, req.params.id
    );

    req.session.flashSuccess = 'Article updated successfully!';
    res.redirect('/admin/posts');
  } catch (err) {
    console.error('Error updating post:', err);
    req.session.flashError = 'Error: ' + err.message;
    res.redirect(`/admin/posts/${req.params.id}/edit`);
  }
});

// Admin Posts: Delete
app.post('/admin/posts/:id/delete', requireAdmin, (req, res) => {
  db.prepare('DELETE FROM posts WHERE id = ?').run(req.params.id);
  req.session.flashSuccess = 'Article deleted successfully.';
  res.redirect('/admin/posts');
});

// Admin Projects: List
app.get('/admin/projects', requireAdmin, (req, res) => {
  const projects = db.prepare('SELECT * FROM projects ORDER BY id DESC').all();
  res.renderWithLayout('admin/projects/index', {
    activeAdminNav: 'projects',
    pageTitle: 'Projects',
    projects,
  }, 'layouts/admin');
});

// Admin Projects: Create Form
app.get('/admin/projects/new', requireAdmin, (req, res) => {
  res.renderWithLayout('admin/projects/form', {
    activeAdminNav: 'projects',
    pageTitle: 'New Project',
    isEdit: false,
    project: {},
  }, 'layouts/admin');
});

// Admin Projects: Create Action
app.post('/admin/projects', requireAdmin, (req, res) => {
  const {
    title, title_ar, slug, category, status, project_date, is_featured,
    technologies, github_url, live_url, main_image, short_description, description, problem, solution
  } = req.body;

  const cleanSlug = slugify(slug || title);

  try {
    db.prepare(`
      INSERT INTO projects (
        title, title_ar, slug, category, status, project_date, is_featured,
        technologies, github_url, live_url, main_image, short_description, short_description_ar,
        description, description_ar, problem, solution
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    `).run(
      title.trim(), title_ar ? title_ar.trim() : title.trim(), cleanSlug, category, status,
      project_date, is_featured ? 1 : 0, technologies, github_url, live_url, main_image,
      short_description, short_description, description, description, problem, solution
    );

    req.session.flashSuccess = 'Project added successfully!';
    res.redirect('/admin/projects');
  } catch (err) {
    req.session.flashError = 'Error: ' + err.message;
    res.redirect('/admin/projects/new');
  }
});

// Admin Projects: Edit Form
app.get('/admin/projects/:id/edit', requireAdmin, (req, res) => {
  const project = db.prepare('SELECT * FROM projects WHERE id = ?').get(req.params.id);
  if (!project) return res.redirect('/admin/projects');

  res.renderWithLayout('admin/projects/form', {
    activeAdminNav: 'projects',
    pageTitle: `Edit: ${project.title}`,
    isEdit: true,
    project,
  }, 'layouts/admin');
});

// Admin Projects: Update Action
app.post('/admin/projects/:id', requireAdmin, (req, res) => {
  const {
    title, title_ar, slug, category, status, project_date, is_featured,
    technologies, github_url, live_url, main_image, short_description, description, problem, solution
  } = req.body;

  const cleanSlug = slugify(slug || title);

  try {
    db.prepare(`
      UPDATE projects SET
        title = ?, title_ar = ?, slug = ?, category = ?, status = ?, project_date = ?, is_featured = ?,
        technologies = ?, github_url = ?, live_url = ?, main_image = ?, short_description = ?,
        description = ?, problem = ?, solution = ?, updated_at = CURRENT_TIMESTAMP
      WHERE id = ?
    `).run(
      title.trim(), title_ar ? title_ar.trim() : title.trim(), cleanSlug, category, status,
      project_date, is_featured ? 1 : 0, technologies, github_url, live_url, main_image,
      short_description, description, problem, solution, req.params.id
    );

    req.session.flashSuccess = 'Project updated successfully!';
    res.redirect('/admin/projects');
  } catch (err) {
    req.session.flashError = 'Error: ' + err.message;
    res.redirect(`/admin/projects/${req.params.id}/edit`);
  }
});

// Admin Projects: Delete
app.post('/admin/projects/:id/delete', requireAdmin, (req, res) => {
  db.prepare('DELETE FROM projects WHERE id = ?').run(req.params.id);
  req.session.flashSuccess = 'Project deleted successfully.';
  res.redirect('/admin/projects');
});

// Admin Categories
app.get('/admin/categories', requireAdmin, (req, res) => {
  const categories = db.prepare(`
    SELECT categories.*, COUNT(posts.id) as posts_count
    FROM categories
    LEFT JOIN posts ON categories.id = posts.category_id
    GROUP BY categories.id
    ORDER BY categories.name ASC
  `).all();
  res.renderWithLayout('admin/categories/index', {
    activeAdminNav: 'categories',
    pageTitle: 'Categories',
    categories,
  }, 'layouts/admin');
});

app.get('/admin/categories/new', requireAdmin, (req, res) => {
  res.renderWithLayout('admin/categories/form', {
    activeAdminNav: 'categories',
    pageTitle: 'New Category',
    isEdit: false,
    category: {},
  }, 'layouts/admin');
});

app.post('/admin/categories', requireAdmin, (req, res) => {
  const { name, name_ar, slug, description, description_ar } = req.body;
  try {
    db.prepare(`
      INSERT INTO categories (name, name_ar, slug, description, description_ar)
      VALUES (?, ?, ?, ?, ?)
    `).run(name.trim(), name_ar.trim(), slugify(slug || name), description, description_ar);
    req.session.flashSuccess = 'Category created.';
    res.redirect('/admin/categories');
  } catch (err) {
    req.session.flashError = err.message;
    res.redirect('/admin/categories/new');
  }
});

app.get('/admin/categories/:id/edit', requireAdmin, (req, res) => {
  const category = db.prepare('SELECT * FROM categories WHERE id = ?').get(req.params.id);
  if (!category) return res.redirect('/admin/categories');
  res.renderWithLayout('admin/categories/form', {
    activeAdminNav: 'categories',
    pageTitle: `Edit: ${category.name}`,
    isEdit: true,
    category,
  }, 'layouts/admin');
});

app.post('/admin/categories/:id', requireAdmin, (req, res) => {
  const { name, name_ar, slug, description, description_ar } = req.body;
  db.prepare(`
    UPDATE categories SET name = ?, name_ar = ?, slug = ?, description = ?, description_ar = ?, updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
  `).run(name.trim(), name_ar.trim(), slugify(slug || name), description, description_ar, req.params.id);
  req.session.flashSuccess = 'Category updated.';
  res.redirect('/admin/categories');
});

app.post('/admin/categories/:id/delete', requireAdmin, (req, res) => {
  db.prepare('DELETE FROM categories WHERE id = ?').run(req.params.id);
  req.session.flashSuccess = 'Category deleted.';
  res.redirect('/admin/categories');
});

// Admin Tags
app.get('/admin/tags', requireAdmin, (req, res) => {
  const tags = db.prepare('SELECT * FROM tags ORDER BY name ASC').all();
  res.renderWithLayout('admin/tags/index', {
    activeAdminNav: 'tags',
    pageTitle: 'Tags',
    tags,
  }, 'layouts/admin');
});

app.post('/admin/tags', requireAdmin, (req, res) => {
  const { name, name_ar, slug } = req.body;
  try {
    db.prepare('INSERT INTO tags (name, name_ar, slug) VALUES (?, ?, ?)').run(
      name.trim(), name_ar ? name_ar.trim() : name.trim(), slugify(slug || name)
    );
    req.session.flashSuccess = 'Tag created.';
  } catch (err) {
    req.session.flashError = err.message;
  }
  res.redirect('/admin/tags');
});

app.post('/admin/tags/:id/delete', requireAdmin, (req, res) => {
  db.prepare('DELETE FROM tags WHERE id = ?').run(req.params.id);
  req.session.flashSuccess = 'Tag deleted.';
  res.redirect('/admin/tags');
});

// Admin Services
app.get('/admin/services', requireAdmin, (req, res) => {
  const services = db.prepare('SELECT * FROM services ORDER BY sort_order ASC, id ASC').all();
  res.renderWithLayout('admin/services/index', {
    activeAdminNav: 'services',
    pageTitle: 'Services',
    services,
  }, 'layouts/admin');
});

app.get('/admin/services/new', requireAdmin, (req, res) => {
  res.renderWithLayout('admin/services/form', {
    activeAdminNav: 'services',
    pageTitle: 'New Service',
    isEdit: false,
    service: {},
  }, 'layouts/admin');
});

app.post('/admin/services', requireAdmin, (req, res) => {
  const { title, title_ar, slug, sort_order, short_description, description } = req.body;
  db.prepare(`
    INSERT INTO services (title, title_ar, slug, sort_order, short_description, description)
    VALUES (?, ?, ?, ?, ?, ?)
  `).run(title.trim(), title_ar ? title_ar.trim() : title.trim(), slugify(slug || title), sort_order || 0, short_description, description);
  req.session.flashSuccess = 'Service created.';
  res.redirect('/admin/services');
});

app.get('/admin/services/:id/edit', requireAdmin, (req, res) => {
  const service = db.prepare('SELECT * FROM services WHERE id = ?').get(req.params.id);
  res.renderWithLayout('admin/services/form', {
    activeAdminNav: 'services',
    pageTitle: `Edit: ${service.title}`,
    isEdit: true,
    service,
  }, 'layouts/admin');
});

app.post('/admin/services/:id', requireAdmin, (req, res) => {
  const { title, title_ar, slug, sort_order, short_description, description } = req.body;
  db.prepare(`
    UPDATE services SET title = ?, title_ar = ?, slug = ?, sort_order = ?, short_description = ?, description = ?, updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
  `).run(title.trim(), title_ar ? title_ar.trim() : title.trim(), slugify(slug || title), sort_order || 0, short_description, description, req.params.id);
  req.session.flashSuccess = 'Service updated.';
  res.redirect('/admin/services');
});

app.post('/admin/services/:id/delete', requireAdmin, (req, res) => {
  db.prepare('DELETE FROM services WHERE id = ?').run(req.params.id);
  req.session.flashSuccess = 'Service deleted.';
  res.redirect('/admin/services');
});

// Admin Messages
app.get('/admin/messages', requireAdmin, (req, res) => {
  const messages = db.prepare('SELECT * FROM messages ORDER BY id DESC').all();
  res.renderWithLayout('admin/messages/index', {
    activeAdminNav: 'messages',
    pageTitle: 'Messages',
    messages,
  }, 'layouts/admin');
});

app.get('/admin/messages/:id', requireAdmin, (req, res) => {
  const msg = db.prepare('SELECT * FROM messages WHERE id = ?').get(req.params.id);
  if (!msg) return res.redirect('/admin/messages');
  // Mark as read
  db.prepare("UPDATE messages SET status = 'read' WHERE id = ? AND status = 'unread'").run(msg.id);
  res.renderWithLayout('admin/messages/show', {
    activeAdminNav: 'messages',
    pageTitle: `Message from ${msg.name}`,
    msg,
  }, 'layouts/admin');
});

app.post('/admin/messages/:id/toggle', requireAdmin, (req, res) => {
  const msg = db.prepare('SELECT status FROM messages WHERE id = ?').get(req.params.id);
  const newStatus = msg && msg.status === 'unread' ? 'read' : 'unread';
  db.prepare('UPDATE messages SET status = ? WHERE id = ?').run(newStatus, req.params.id);
  res.redirect('/admin/messages/' + req.params.id);
});

app.post('/admin/messages/:id/delete', requireAdmin, (req, res) => {
  db.prepare('DELETE FROM messages WHERE id = ?').run(req.params.id);
  req.session.flashSuccess = 'Message deleted.';
  res.redirect('/admin/messages');
});

// Admin Settings
app.get('/admin/settings', requireAdmin, (req, res) => {
  res.renderWithLayout('admin/settings/index', {
    activeAdminNav: 'settings',
    pageTitle: 'Settings',
  }, 'layouts/admin');
});

app.post('/admin/settings', requireAdmin, (req, res) => {
  const updateSetting = db.prepare('INSERT OR REPLACE INTO settings (key, value, group_name) VALUES (?, ?, ?)');
  const payload = req.body;
  if (!payload.adsense_enabled) {
    payload.adsense_enabled = '0';
  }

  for (const [key, val] of Object.entries(payload)) {
    updateSetting.run(key, val, 'general');
  }

  clearSettingsCache();
  req.session.flashSuccess = 'Settings saved successfully!';
  res.redirect('/admin/settings');
});

// 404 Handler
app.use((req, res) => {
  res.status(404).renderWithLayout('errors/404', {
    activePage: '',
    seo: {
      title: '404 - Page Not Found | DheyaDev',
      description: 'Page not found.',
      noindex: true,
      canonical: `${res.locals.baseUrl}${req.path}`,
      ogImage: `${res.locals.baseUrl}/images/og-cover.png`,
    }
  });
});

// 500 Handler
app.use((err, req, res, next) => {
  console.error('Server error:', err);
  res.status(500).renderWithLayout('errors/500', {
    activePage: '',
    seo: {
      title: '500 - Server Error | DheyaDev',
      description: 'Server error occurred.',
      noindex: true,
      canonical: `${res.locals.baseUrl}${req.path}`,
      ogImage: `${res.locals.baseUrl}/images/og-cover.png`,
    }
  });
});

// Start Server on 0.0.0.0
const server = app.listen(PORT, '0.0.0.0', () => {
  console.log(`DheyaDev Platform listening on http://0.0.0.0:${PORT}`);
});

module.exports = { app, server };
