

    <h1>Domain & Hosting Portfolio Manager</h1>

    <p>The <strong>Domain & Hosting Portfolio Manager</strong> is a simple yet efficient PHP-based script designed to help you keep track of your domains and hosting renewal dates. This tool is especially helpful for individuals and businesses managing multiple domains and hosting accounts. With this script, you can store information such as domain expiry dates, hosting plans, providers, renewal costs, and receive reminders when renewals are approaching.</p>

    <h2>Key Features:</h2>
    <ul>
        <li><strong>Manage Domains & Hosting:</strong> Store and update details of all your domains and hosting services.</li>
        <li><strong>Track Renewal Dates:</strong> Easily view renewal dates for both domains and hosting services, ensuring that you never miss a renewal.</li>
        <li><strong>Admin Login:</strong> Access the system securely using predefined login credentials.</li>
        <li><strong>Customizable:</strong> The system is designed to be lightweight, but you can extend its functionality as per your requirements.</li>
    </ul>

    <h2>Default Admin Credentials:</h2>
    <ul>
        <li><strong>Username:</strong> admin</li>
        <li><strong>Password:</strong> admin123 (Note: Password is stored using PHP’s <code>password_hash()</code> function for security.)</li>
    </ul>

    <h2>Screenshots:</h2>
    <h3>1. Dashboard View</h3>
    <img src="https://github.com/mstripathi/Domain-and-Hosting-Manager/blob/main/SCREEN-1.png" alt="Dashboard View">

    <h3>2. Domain & Hosting Manager</h3>
    <img src="https://github.com/mstripathi/Domain-and-Hosting-Manager/blob/main/SCREEN-2.png" alt="Domain & Hosting Manager">

    <h2>Installation Steps:</h2>

        <h3>To install the script, follow these steps:</h3>
        <ol>
            <li><strong>Download the Script:</strong> Clone or download the repository from GitHub.</li>
            <li><strong>Set Up Your Database:</strong>
                <ul>
                    <li>Log in to <code>phpMyAdmin</code>.</li>
                    <li>Create a new database (e.g., <code>domain_manager</code>).</li>
                    <li>Run the SQL script provided in the repository to create necessary tables.</li>
                </ul>
            </li>
            <li><strong>Configure Database Connection:</strong>
                <ul>
                    <li>Edit the <code>config.php</code> file (or similar) to include your database credentials.</li>
                </ul>
            </li>
            <li><strong>Upload the Script to Your Server:</strong>
                <ul>
                    <li>Use FTP or file manager to upload the script files to your server.</li>
                </ul>
            </li>
            <li><strong>Access the Application:</strong> Open your web browser and navigate to the location where you uploaded the script. Log in using the default credentials.</li>
        </ol>
   
