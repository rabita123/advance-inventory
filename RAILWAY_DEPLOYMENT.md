# Railway Deployment Configuration

## Environment Variables to set in Railway:

**IMPORTANT: Copy these exact variables to Railway:**

```env
APP_NAME=Advanced Inventory System
APP_ENV=production
APP_KEY=base64:DHBSBmFMB7HlGANfJj/KEFvLbZ5oxXTg9cyUmxSH5/Q=
APP_DEBUG=false
APP_URL=https://your-app-name.up.railway.app

DB_CONNECTION=mysql
DB_HOST=${{MYSQLHOST}}
DB_PORT=${{MYSQLPORT}}
DB_DATABASE=${{MYSQLDATABASE}}
DB_USERNAME=${{MYSQLUSER}}
DB_PASSWORD=${{MYSQLPASSWORD}}

CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

## Deployment Steps:

1. ✅ **GitHub Push Complete**
2. 🔄 **Create Railway Project** - Deploy from GitHub repo: `rabita123/advance-inventory`
3. 🔄 **Add MySQL Database** - Click "New" → "Database" → "MySQL"
4. 🔄 **Set Environment Variables** - Copy variables above to Railway
5. 🔄 **Deploy** - Railway will automatically build and deploy
6. 🔄 **Run Migrations** - Use Railway's console to run migrations

## After Deployment:

- Your app will be available at: `https://your-app-name.up.railway.app`
- Railway automatically handles SSL certificates
- Database will be automatically configured through environment variables