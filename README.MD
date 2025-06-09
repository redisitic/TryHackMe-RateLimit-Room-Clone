#  Hammer Room Clone (Rate Limiting CTF)

This project is a reverse-engineered clone of the **TryHackMe Hammer Room**, designed for educational and internship purposes. It demonstrates poor implementation of rate limiting and exposes flaws like brute-force vulnerabilities, weak session handling, and bypassable protections.


## Features

- Hardcoded user registration and login functionality via MySQL
- 4-digit reset code stored in session and brute-forceable
- No real rate limiting — simulates vulnerable behavior
- Supports bypass using `X-Forwarded-For` header

---

##  Setup Instructions (XAMPP)

### 1. Clone / Copy Project

Place this directory inside:
C:\xampp\htdocs\hmr_clone

pgsql
Copy
Edit

### 2. Start MySQL & Apache (via XAMPP Control Panel)

### 3. Import the Database

- Open `phpMyAdmin` at [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
- Create a DB named `hammer`
- Import `hammer.sql` included with this project

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL
);
```
4. Register a User
Use Postman:

```bash
POST http://localhost/hmr_clone/auth.php?register

Headers:
  Content-Type: application/json

Body:
{
  "email": "user@hmr.thm",
  "password": "password123"
}
```
