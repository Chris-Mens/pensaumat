<?php
require_once 'db_connect.php';

function createTable($conn, $sql, $name) {
    if ($conn->query($sql) === TRUE) {
        echo "Table '$name' created/checked successfully.<br>";
    } else {
        echo "Error creating table '$name': " . $conn->error . "<br>";
    }
}

// 1. CMS Pages (for Home, About, etc.)
$sql = "CREATE TABLE IF NOT EXISTS cms_pages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    page_slug VARCHAR(50) NOT NULL UNIQUE, -- e.g., 'home', 'about', 'wings'
    section_name VARCHAR(50) NOT NULL, -- e.g., 'welcome_message', 'vision'
    content_text TEXT,
    content_html LONGTEXT,
    image_url VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
createTable($conn, $sql, 'cms_pages');

// 2. Announcements
$sql = "CREATE TABLE IF NOT EXISTS announcements (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(20) DEFAULT 'general', -- general, alert, update
    is_pinned BOOLEAN DEFAULT FALSE,
    expires_at DATETIME NULL,
    status VARCHAR(20) DEFAULT 'active',
    created_by INT(11),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
createTable($conn, $sql, 'announcements');

// 3. Departments/Ministries
$sql = "CREATE TABLE IF NOT EXISTS departments (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    leader_name VARCHAR(100),
    leader_contact VARCHAR(100),
    meeting_day VARCHAR(50),
    meeting_time VARCHAR(50),
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
createTable($conn, $sql, 'departments');

// 4. Site Settings (Contact info, Social links, SEO defaults)
$sql = "CREATE TABLE IF NOT EXISTS site_settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
createTable($conn, $sql, 'site_settings');

// 5. Contact Messages (Inbox)
$sql = "CREATE TABLE IF NOT EXISTS contact_messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    subject VARCHAR(200),
    message TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
createTable($conn, $sql, 'contact_messages');

// Seed some initial CMS data if empty
$check = $conn->query("SELECT * FROM cms_pages LIMIT 1");
if ($check->num_rows == 0) {
    $conn->query("INSERT INTO cms_pages (page_slug, section_name, content_text) VALUES 
        ('home', 'hero_title', 'Welcome to PENSA UMaT'),
        ('home', 'hero_subtitle', 'Raising Christ-like Disciples'),
        ('about', 'vision', 'To be a global penthouse of raising Christ-like disciples.'),
        ('about', 'mission', 'To coordinate the activities of all Pentecost Students and Associates.')
    ");
    echo "Seeded initial CMS content.<br>";
}

// Seed Site Settings
$check_settings = $conn->query("SELECT * FROM site_settings LIMIT 1");
if ($check_settings->num_rows == 0) {
    $conn->query("INSERT INTO site_settings (setting_key, setting_value) VALUES 
        ('contact_phone', '+233 123 456 789'),
        ('contact_email', 'info@pensaumat.org'),
        ('contact_address', 'UMaT Campus, Tarkwa'),
        ('site_title', 'PENSA UMaT')
    ");
    echo "Seeded site settings.<br>";
}

echo "CMS Setup Complete.";
$conn->close();
?>
