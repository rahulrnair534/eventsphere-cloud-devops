-- EventSphere schema + sample data (MySQL 8 / RDS)
CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE, phone VARCHAR(20) NOT NULL,
  password VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB;
CREATE TABLE admins (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL) ENGINE=InnoDB;
CREATE TABLE categories (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, name VARCHAR(80) NOT NULL UNIQUE) ENGINE=InnoDB;
CREATE TABLE events (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, title VARCHAR(150) NOT NULL, description TEXT, category_id INT UNSIGNED NOT NULL,
  location VARCHAR(100) NOT NULL, venue VARCHAR(150), event_date DATETIME NOT NULL, capacity INT UNSIGNED NOT NULL DEFAULT 100, image VARCHAR(255),
  published TINYINT(1) NOT NULL DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_date (event_date), INDEX idx_pub (published, event_date), INDEX idx_loc (location),
  FOREIGN KEY (category_id) REFERENCES categories(id)) ENGINE=InnoDB;
CREATE TABLE registrations (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, user_id INT UNSIGNED NOT NULL, event_id INT UNSIGNED NOT NULL,
  status ENUM('registered','cancelled') NOT NULL DEFAULT 'registered', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uq_user_event (user_id, event_id), INDEX idx_event (event_id, status),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE) ENGINE=InnoDB;

-- Default admin: admin@eventsphere.com / password  (CHANGE AFTER FIRST LOGIN)
INSERT INTO admins(name,email,password) VALUES('Administrator','admin@eventsphere.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
INSERT INTO categories(name) VALUES('Technology'),('Music'),('Business'),('Workshop'),('Sports');
INSERT INTO events(title,description,category_id,location,venue,event_date,capacity,image) VALUES
('Cloud & DevOps Summit','A full-day summit on AWS, Docker, CI/CD and infrastructure as code.',1,'Bengaluru','Tech Park Auditorium',DATE_ADD(NOW(),INTERVAL 10 DAY),200,'https://picsum.photos/seed/cloud/600/360'),
('Live Indie Music Night','An evening of live performances from local indie bands.',2,'Kochi','Marine Drive Arena',DATE_ADD(NOW(),INTERVAL 5 DAY),150,'https://picsum.photos/seed/music/600/360'),
('Startup Pitch Day','Founders pitch to investors. Networking lunch included.',3,'Mumbai','Startup Hub',DATE_ADD(NOW(),INTERVAL 15 DAY),80,'https://picsum.photos/seed/startup/600/360'),
('PHP & MySQL Hands-on Workshop','Build and deploy a small web app step by step.',4,'Thiruvananthapuram','Technopark Hall 2',DATE_ADD(NOW(),INTERVAL 7 DAY),40,'https://picsum.photos/seed/php/600/360'),
('City Marathon 10K','Community 10K run for all fitness levels.',5,'Chennai','Marina Beach',DATE_ADD(NOW(),INTERVAL 20 DAY),500,'https://picsum.photos/seed/run/600/360'),
('AI & Data Meetup','Talks and demos on practical machine learning.',1,'Hyderabad','Innovation Center',DATE_ADD(NOW(),INTERVAL 12 DAY),60,'https://picsum.photos/seed/ai/600/360'),
('Past: Web Dev Bootcamp','A finished event (shows in past history).',4,'Kochi','Digital Hub',DATE_SUB(NOW(),INTERVAL 10 DAY),50,'https://picsum.photos/seed/web/600/360');
