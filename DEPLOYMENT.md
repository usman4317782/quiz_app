# Deployment Instructions

## Database Configuration
The application uses environment-based configuration to switch between local and production database credentials securely.

### Files
- `.env`: Contains local development credentials. **Do not deploy this file to production.**
- `.env.production`: Contains production credentials. This file is automatically loaded when the application detects it is running on `mindtechnology.online`.

### Environment Detection
The application automatically detects the environment based on the domain name:
- **Production**: `mindtechnology.online`
- **Local**: `localhost` or any other domain.

### How to Deploy
1. Upload all files to the server, excluding `.env` and `.git` folder.
2. Ensure `.env.production` is present in the root directory.
3. The application will automatically load the credentials from `.env.production`.

### Manual Configuration (Optional)
If you prefer not to use `.env.production` on the server, you can set the environment variables directly in your server configuration (e.g., Apache SetEnv or PHP-FPM):

```apache
SetEnv DB_HOST localhost
SetEnv DB_NAME mindtechnology_qiuz_app
SetEnv DB_USER mindtechnology_qiuz_app
SetEnv DB_PASS Y@Usman00
SetEnv APP_ENV production
```
