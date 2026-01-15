-- BẢNG PHÂN QUYỀN
CREATE TABLE roles (
    roleID INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(50) NOT NULL UNIQUE
);

INSERT INTO roles (role_name) VALUES
('ADMIN'),
('MANAGER'),
('MEMBER');

-- BẢNG NGƯỜI DÙNG
CREATE TABLE users (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    full_name VARCHAR(100),
    google_id VARCHAR(255) UNIQUE,
    roleID INT NOT NULL,
    status TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (roleID) REFERENCES roles(roleID)
        ON UPDATE CASCADE
);



-- BẢNG CÂU LẠC BỘ
CREATE TABLE clubs (
    clubID INT AUTO_INCREMENT PRIMARY KEY,
    club_name VARCHAR(100) NOT NULL,
    founded_date DATE -- ngày thành lập
);


-- BẢNG MÔN THỂ THAO
CREATE TABLE sports (
    sportID INT AUTO_INCREMENT PRIMARY KEY,
    sport_name VARCHAR(100) NOT NULL
);

INSERT INTO sports (sport_name) VALUES
('Bóng đá'),
('Cầu lông'),
('Bóng bàn'),
('Bóng chuyền'),
('Bóng rổ');


-- BẢNG CLB - MÔN THỂ THAO
CREATE TABLE club_sports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    clubID INT NOT NULL,
    sportID INT NOT NULL,

    UNIQUE (clubID, sportID),
    FOREIGN KEY (clubID) REFERENCES clubs(clubID)
        ON DELETE CASCADE,
    FOREIGN KEY (sportID) REFERENCES sports(sportID)
        ON DELETE CASCADE
);

INSERT INTO club_sports (clubID, sportID)
VALUES
(1, (SELECT sportID FROM sports WHERE sport_name = 'Bóng đá')),
(2, (SELECT sportID FROM sports WHERE sport_name = 'Bóng bàn'));



-- BẢNG THÀNH VIÊN CÂU LẠC BỘ
CREATE TABLE club_members (
    userID INT NOT NULL,
    clubID INT NOT NULL,
    join_date DATE,
    fee_paid_date DATE,
    fee_expire_date DATE,
    status TINYINT DEFAULT 1,

    PRIMARY KEY (userID, clubID),
    FOREIGN KEY (userID) REFERENCES users(userID),
    FOREIGN KEY (clubID) REFERENCES clubs(clubID)
);


-- BẢNG SÂN 
CREATE TABLE grounds (
    groundID INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    location VARCHAR(255),
    status TINYINT DEFAULT 1
);


-- BẢNG ĐẶT SÂN 
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    booking_date DATE NOT NULL,
    groundID  INT NOT NULL
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    UNIQUE (groundID, booking_date, start_time, end_time),

    FOREIGN KEY (groundID) REFERENCES grounds(groundID),
    FOREIGN KEY (userID) REFERENCES users(userID),
    CHECK (start_time < end_time)
);

