
<?php
$host = 'localhost';
$db   = 'library';
$user = 'root'; 
$pass = '';

 $conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}









/*CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user','librarian') NOT NULL,

    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,

    school_id VARCHAR(200) NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

     
);


CREATE TABLE schools (
    id INT AUTO_INCREMENT PRIMARY KEY,

    school_name VARCHAR(255) NOT NULL,
    school_code VARCHAR(20) UNIQUE NOT NULL,  -- GENERATED ID
    librarian_id INT UNIQUE NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (librarian_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(150) NOT NULL,
    category VARCHAR(100) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    school_id INT NOT NULL,      -- relates to librarian's school
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

    FOREIGN KEY (school_id) REFERENCES(id) ON DELETE CASCADE

    
);

 */

?>
