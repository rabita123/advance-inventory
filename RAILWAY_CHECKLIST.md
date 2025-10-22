# Railway Deployment Checklist

## Pre-Deployment Checklist

- [ ] Code is pushed to Git repository (GitHub/GitLab/Bitbucket)
- [ ] Railway account created and logged in
- [ ] All Railway configuration files are present:
  - [ ] `railway.json` ✓
  - [ ] `Procfile` ✓
  - [ ] `railway.sh` ✓
  - [ ] `RAILWAY_DEPLOYMENT.md` ✓

## Deployment Steps

1. **Connect Repository**
   - [ ] Create new Railway project
   - [ ] Connect your Git repository
   - [ ] Verify project is detected as Laravel

2. **Add Database**
   - [ ] Add MySQL database service
   - [ ] Note database connection details

3. **Configure Environment Variables**
   - [ ] Set `APP_NAME="Advanced Inventory"`
   - [ ] Set `APP_ENV=production`
   - [ ] Leave `APP_KEY` empty (auto-generated)
   - [ ] Set `APP_DEBUG=false`
   - [ ] Set `APP_URL=https://your-app-name.up.railway.app`
   - [ ] Configure database variables (auto-provided by Railway)
   - [ ] Set cache and session drivers
   - [ ] Configure mail settings (if needed)

4. **Deploy**
   - [ ] Trigger deployment
   - [ ] Monitor build logs
   - [ ] Verify deployment success

5. **Post-Deployment**
   - [ ] Test application URL
   - [ ] Verify database connection
   - [ ] Check application functionality
   - [ ] Run database seeders (if needed)

## Quick Commands for Railway Terminal

```bash
# Check application status
php artisan about

# Run migrations (if not done automatically)
php artisan migrate --force

# Seed database
php artisan db:seed

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check logs
tail -f storage/logs/laravel.log
```

## Troubleshooting Commands

```bash
# Check environment variables
php artisan env

# Check database connection
php artisan tinker
# Then run: DB::connection()->getPdo();

# Check application key
php artisan key:generate --show

# Check routes
php artisan route:list
```
