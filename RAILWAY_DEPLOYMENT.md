# Railway Deployment Guide for Advanced Inventory

This guide will help you deploy your Laravel Advanced Inventory application to Railway.

## Prerequisites

1. A Railway account (sign up at [railway.app](https://railway.app))
2. Your project code pushed to a Git repository (GitHub, GitLab, or Bitbucket)
3. A MySQL database (Railway provides MySQL add-ons)

## Step 1: Connect Your Repository

1. Log in to Railway
2. Click "New Project"
3. Select "Deploy from GitHub repo" (or your preferred Git provider)
4. Choose your repository containing the Advanced Inventory project

## Step 2: Add MySQL Database

1. In your Railway project dashboard, click "New"
2. Select "Database" → "MySQL"
3. Railway will automatically provision a MySQL database
4. Note down the database connection details (you'll need these for environment variables)

## Step 3: Configure Environment Variables

In your Railway project settings, go to the "Variables" tab and add the following environment variables:

### Required Environment Variables

```env
APP_NAME="Advanced Inventory"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

# Database Configuration (Railway will provide these automatically)
DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

# Cache and Session Configuration
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail Configuration (optional - configure if you need email functionality)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"

# Logging
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Important Notes:

- **APP_KEY**: Leave this empty initially. Railway will generate it automatically during deployment.
- **APP_URL**: Replace `your-app-name` with your actual Railway app name
- **Database Variables**: Railway automatically provides these when you add a MySQL database
- **MAIL_***: Configure these only if you need email functionality

## Step 4: Deploy Your Application

1. Railway will automatically detect your Laravel application
2. The deployment will use the `railway.json` configuration
3. Railway will run the build process:
   - Install PHP dependencies with Composer
   - Install Node.js dependencies
   - Build frontend assets with Vite
   - Generate application key
   - Cache configuration
   - Run database migrations

## Step 5: Verify Deployment

1. Once deployment is complete, Railway will provide you with a URL
2. Visit the URL to verify your application is running
3. Check the logs in Railway dashboard if there are any issues

## Step 6: Database Setup

If you need to seed your database with initial data:

1. Go to your Railway project dashboard
2. Click on your app service
3. Go to the "Deployments" tab
4. Click on the latest deployment
5. Open the terminal and run:
   ```bash
   php artisan db:seed
   ```

## Troubleshooting

### Common Issues:

1. **Application Key Error**: Make sure `APP_KEY` is empty in environment variables (Railway will generate it)

2. **Database Connection Issues**: 
   - Verify database variables are correctly set
   - Check that MySQL service is running in Railway

3. **Asset Loading Issues**:
   - Ensure `npm run build` completed successfully
   - Check that Vite build process ran without errors

4. **Permission Issues**:
   - The deployment script sets proper permissions automatically
   - If issues persist, check Railway logs

### Checking Logs:

1. In Railway dashboard, go to your app service
2. Click on "Deployments"
3. Select the latest deployment
4. View logs to identify any issues

## File Structure

Your project should have these Railway-specific files:

- `railway.json` - Railway configuration
- `Procfile` - Process definition
- `railway.sh` - Deployment script
- `RAILWAY_DEPLOYMENT.md` - This guide

## Environment-Specific Configuration

### Production Optimizations:

- `APP_DEBUG=false` - Disable debug mode
- `APP_ENV=production` - Set production environment
- `LOG_LEVEL=error` - Only log errors
- Caching is enabled for config, routes, and views

### Security Considerations:

- Never commit `.env` files to version control
- Use Railway's environment variables for sensitive data
- Ensure `APP_DEBUG=false` in production
- Use HTTPS URLs for `APP_URL`

## Scaling and Performance

Railway automatically handles:
- Load balancing
- SSL certificates
- CDN for static assets
- Auto-scaling based on traffic

## Support

If you encounter issues:
1. Check Railway documentation: [docs.railway.app](https://docs.railway.app)
2. Review deployment logs
3. Verify environment variables
4. Check Laravel logs in the application

## Next Steps

After successful deployment:
1. Set up custom domain (optional)
2. Configure monitoring and alerts
3. Set up automated backups for your database
4. Configure CI/CD for automatic deployments