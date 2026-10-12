# Columbia Basin Chiropractic — Website Backend + Dashboard

The backend for **columbiabasinchiropractic.com**, with staging at **new.columbiabasinchiropractic.com**. It includes:

- an app-style dashboard at `/admin/`
- a JSON API
- the database layer, media library and forms
- SEO and schema output
- a web installer and content migrations
- a GitHub Actions deploy to SiteGround

It is plain PHP 8 + MySQL (SQLite for local development), with no framework and no build step on the server.

The public templates in `app/views/` and `assets/` are a deliberately plain **scaffold**: they render every content type so the site works end to end. Replace them with the real design. **`BACKEND.md` is the full guide.**

## Local development

```bash
npm install                      # esbuild only (minifies assets/css/site.css and assets/js/site.js)
php app/cli/install.php --driver=sqlite --username=admin --email=you@example.com --password='choose-one'
php -S 127.0.0.1:8080 index.php  # site: http://127.0.0.1:8080  dashboard: /admin/
npm run build                    # after CSS/JS edits
```

## Deploy

Run GitHub → Actions → **Deploy to SiteGround** → Run workflow. Tick **first_deploy** the first time, then open `/install/`. See `BACKEND.md` §9.
