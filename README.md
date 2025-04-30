# securesite
A secure PHP-based login system featuring session management, prepared statements, XSS and CSRF protection, rate limiting, 2FA, and timeout handling. Detailed documentations are provided in the seurity documentation.

# Setup

Clone the repository.
Ensure you have PHP and MySQL set up locally.
Create a MySQL database named securesite.
Import the provided sqlsetup.sql file to initialize the required tables.
Update db.php with your database credentials.
Optionally test db with dbtest.php
Run the site locally via a local server (ran through apache after sending all files to /var/www/html)
