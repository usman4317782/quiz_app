# Security Documentation

This document outlines the security measures implemented in the Quiz App login and registration system.

## 1. Password Hashing
- **Algorithm**: Passwords are hashed using the `password_hash()` function with the default algorithm (currently BCRYPT).
- **Verification**: Passwords are verified using `password_verify()` during login.
- **Storage**: Only the hash is stored in the database, never the plain-text password.

## 2. SQL Injection Prevention
- **Method**: All database interactions use PHP Data Objects (PDO) with prepared statements.
- **Implementation**: User input is never concatenated directly into SQL queries. Instead, named placeholders (e.g., `:username`) are used, and values are bound during execution.

## 3. Cross-Site Scripting (XSS) Protection
- **Output Escaping**: User-generated content (like the username displayed on the dashboard) is escaped using `htmlspecialchars()` before being rendered in the browser. This prevents malicious scripts from being executed.

## 4. Cross-Site Request Forgery (CSRF) Protection
- **Token Generation**: A unique, cryptographically secure random token is generated for each user session using `bin2hex(random_bytes(32))`.
- **Token Verification**: This token is included as a hidden field in all forms (`register.php`, `index.php`). Upon submission, the backend (`auth_register.php`, `auth_login.php`) verifies that the submitted token matches the session token.

## 5. Session Management
- **Database Storage**: Sessions are stored in the `sessions` database table instead of the file system, allowing for better scalability and management.
- **Secure Configuration**:
    - `HttpOnly`: Enabled to prevent JavaScript access to session cookies.
    - `Secure`: Enabled (if HTTPS is detected) to transmit cookies only over encrypted connections.
    - `SameSite`: Set to 'Strict' to prevent CSRF attacks.
- **Timeouts**:
    - **Idle Timeout**: Sessions expire after 30 minutes of inactivity.
    - **Absolute Timeout**: Session IDs are regenerated every 60 minutes to prevent long-term session hijacking.
- **Fixation Prevention**: `session_regenerate_id(true)` is called immediately upon successful login.

## 6. Rate Limiting (Brute Force Protection)
- **Mechanism**: A `RateLimiter` class tracks failed login attempts by IP address.
- **Policy**: Users are blocked after 5 failed attempts within 15 minutes.
- **Storage**: Failed attempts are recorded in the `login_attempts` table.

## 7. Historical Logging
- **Audit Trail**: All critical authentication events (Login Success, Login Failure, Logout, CSRF Failure) are logged in the `auth_logs` table.
- **Data Points**: Logs include User ID, IP Address, User Agent, Session ID, Timestamp, and specific status messages.
- **Privacy**: Passwords and sensitive data are never logged.

## 8. Input Validation
- **Client-Side**: HTML5 and JavaScript validation provide immediate feedback to users (e.g., email format, password complexity).
- **Server-Side**: All inputs are strictly validated on the server side before processing. This ensures that even if client-side validation is bypassed, bad data is rejected.
    - Username: Alphanumeric only.
    - Email: Valid email format.
    - Password: Minimum complexity requirements.

## 9. Database Security
- **Schema**: The `users` table uses appropriate data types and constraints (`UNIQUE` for username and email) to ensure data integrity.
- **Connection**: The PDO connection is configured to throw exceptions on errors, preventing silent failures and allowing for proper error handling.
- **Isolation**: Database operations for critical paths (like login checks) are isolated to prevent race conditions.
