# Golfs Cameroon — NGO Website & Management System

This project is a PHP + MySQL web application scaffold for Golfs Cameroon (NGO). It contains public pages and an admin area for management.

Tech stack:
- PHP (plain, simple MVC-like structure)
- MySQL (PDO)
- Tailwind CSS (via CDN for prototypes)
- Vanilla JS for small UI features

Quick setup (local XAMPP on Windows):

1. Create database and run `database/schema.sql` to create tables.
2. Update DB credentials in `config/database.php` if needed.
3. Enable GD extension in PHP (for image thumbnails):
   - Edit `php.ini` in your XAMPP installation
   - Uncomment the line: `;extension=gd` → `extension=gd`
   - Restart Apache
4. Place the project in your web root (e.g. `c:/xampp/htdocs/ngo`).
5. Enable mod_rewrite in Apache (the `.htaccess` files handle clean URLs).
6. Create your first admin user:
   - Visit `http://localhost/ngo/admin/setup_admin.php`
   - Fill in username and password
   - Click "Create Admin User"
   - **Delete the setup_admin.php file after creating your account** (for security)
7. Login to admin at `http://localhost/ngo/admin/login.php`
8. Visit `http://localhost/ngo/` for the public site (no `/public` in URL needed).

Clean URLs:
- Home: `http://localhost/ngo/`
- About: `http://localhost/ngo/about`
- Services: `http://localhost/ngo/services`
- Members: `http://localhost/ngo/members`
- Blog: `http://localhost/ngo/blog`
- Donations: `http://localhost/ngo/donations`

**Demo Logins:**

Use the credentials you created in step 5. Example:
- Username: `admin`
- Password: `MySecurePassword123`

Or create an `editor` role user for content-only access (no project/member/donor management).

Notes:
- Admin CRUD interfaces are scaffolded with file uploads, CSV exports, pagination, and bulk delete actions.
- TinyMCE rich editor is used for blog content.
- Clean URLs use Apache mod_rewrite in `.htaccess` files (root and public folders).
- The public pages use a router (`public/index.php`) to map clean URLs to pages.
- For production, configure secure database credentials, HTTPS, enable error logging, and remove `setup_admin.php`.
