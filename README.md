# Domain & Hosting Portfolio Manager

**Domain & Hosting Portfolio Manager** is a simple yet efficient PHP-based script designed to help you keep track of your domains and hosting renewal dates. This tool is especially helpful for individuals and businesses managing multiple domains and hosting accounts. With this script, you can store information such as domain expiry dates, hosting plans, providers, renewal costs, and receive reminders when renewals are approaching.

## Key Features:
- **Manage Domains & Hosting:** Store and update details of all your domains and hosting services.
- **Track Renewal Dates:** Easily view renewal dates for both domains and hosting services, ensuring that you never miss a renewal.
- **Admin Login:** Access the system securely using predefined login credentials.
- **Customizable:** The system is designed to be lightweight, but you can extend its functionality as per your requirements.

## Default Admin Credentials:
- **Username:** `admin`
- **Password:** `admin123`  
  *(Note: Password is stored using PHP’s `password_hash()` function for security.)*

## Screenshots:
### 1. Dashboard View
![Dashboard View](https://github.com/mstripathi/Domain-and-Hosting-Manager/blob/main/SCREEN-3.png)

### 2. Domain & Hosting Manager
![Domain & Hosting Manager](https://github.com/mstripathi/Domain-and-Hosting-Manager/blob/main/SCREEN-2.png)

## Installation Steps:

To install the script, follow these steps:
1. **Download the Script:** Clone or download the repository from GitHub.
2. **Set Up Your Database:**
    - Log in to `phpMyAdmin`.
    - Create a new database (e.g., `domain_manager`).
    - Run the SQL script provided in the repository to create necessary tables.
3. **Configure Database Connection:**
    - Edit the `connection.php` file  to include your database credentials.
4. **Upload the Script to Your Server:**
    - Use FTP or file manager to upload the script files to your server.
5. **Access the Application:** Open your web browser and navigate to the location where you uploaded the script. Log in using the default credentials.

## License
This project is licensed under the MIT License. 
