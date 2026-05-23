# ArenaX Deployment

## Local installation

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

Demo accounts:

- Admin: `admin@playarena.test` / `password`
- Super Admin: `admin@arenax.com` / `Admin@123`
- Vendor: `owner@arenax.test` / `Vendor@123`
- User: `player@playarena.test` / `password`
- Demo OTP: `123456` outside production

## Hostinger shared hosting

1. Create a MySQL database in hPanel and update `.env`.
2. Upload the project outside `public_html` when possible.
3. Point the domain document root to `public`.
4. If document root cannot be changed, copy the contents of `public` into `public_html` and update `index.php` paths to point to the project `vendor/autoload.php` and `bootstrap/app.php`.
5. Run:

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

For shared hosting without workers, use `QUEUE_CONNECTION=sync`. Add real `RAZORPAY_KEY`, `RAZORPAY_SECRET`, `GOOGLE_MAPS_API_KEY`, SMTP settings, Twilio or Fast2SMS OTP keys, `APP_ENV=production`, and `APP_DEBUG=false`.
