# Deploy to Netlify - Static Site Configuration

## Build Settings for Netlify:

**Build Command:**
```bash
npm run build
```

**Publish Directory:**
```
public/build
```

## _redirects file for SPA routing:
```
/*    /index.html   200
```

## netlify.toml configuration:
```toml
[build]
  command = "npm run build"
  publish = "public/build"

[[redirects]]
  from = "/*"
  to = "/index.html"
  status = 200

[build.environment]
  NODE_VERSION = "18"
```

## Steps to deploy:

1. **Push your code to GitHub**
2. **Connect Netlify to your GitHub repo**
3. **Set build settings:**
   - Build command: `npm run build`
   - Publish directory: `public/build`
4. **Add environment variables** in Netlify dashboard
5. **Deploy**

Note: This only works for frontend assets. Your Laravel backend needs to be hosted separately on a PHP-supporting platform like Heroku, Railway, or DigitalOcean.