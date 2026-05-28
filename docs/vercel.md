# Vercel Deployment

This app is a Laravel application, so Vercel runs it through the `vercel-php` community runtime rather than a native PHP platform. Static Vite assets are still built into `public/build`, and all web routes are forwarded through `api/index.php`.

## Files Added

- `api/index.php` forwards Vercel requests into Laravel's normal `public/index.php` entrypoint and moves Laravel writable/cache paths to `/tmp`.
- `vercel.json` configures the PHP function, builds Vite assets, and routes static assets before Laravel.
- `.vercelignore` keeps local dependencies and secrets out of Vercel uploads.
- `.env.vercel.example` lists production variables to add in Vercel.

## Required Vercel Environment Variables

Add these in Vercel Project Settings, for Production and Preview as needed:

```dotenv
APP_NAME=ChopChopClock
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-project.vercel.app

DB_CONNECTION=mysql
DB_HOST=your-mysql-host
DB_PORT=3306
DB_DATABASE=chopchopclock
DB_USERNAME=your-user
DB_PASSWORD=...

SESSION_DRIVER=cookie
CACHE_STORE=array
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
APP_MAINTENANCE_DRIVER=file
```

Generate `APP_KEY` locally:

```bash
php artisan key:generate --show
```

## Database

Vercel does not run MySQL for this app. The public landing page can deploy without a database, but registration, login, the dashboard, settings, and history need an external MySQL-compatible database such as PlanetScale, Railway, Aiven, or an existing managed MySQL service.

After you add database environment variables in Vercel, run migrations against that database:

```bash
php artisan migrate --force
```

## Deploy

Install and log in to the Vercel CLI, then deploy:

```bash
npm install -g vercel
vercel login
vercel
vercel --prod
```

You can also import the GitHub repository in the Vercel dashboard. Keep the build command as `npm run build`; the PHP runtime handles Composer dependencies for the serverless function.
