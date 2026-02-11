CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('user','librarian') NOT NULL,

    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    password VARCHAR(255) NOT NULL,

    school_id INT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (school_id) REFERENCES schools(id)
    ON DELETE SET NULL
);


CREATE TABLE schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(255) NOT NULL,
    librarian_id INT UNIQUE NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (librarian_id) REFERENCES users(id)
    ON DELETE CASCADE
);
