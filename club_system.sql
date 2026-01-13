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


-- BẢNG KHUNG GIỜ
CREATE TABLE time_slots (
    timeID INT AUTO_INCREMENT PRIMARY KEY,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
      CHECK (start_time < end_time)
);


-- BẢNG LỊCH 
CREATE TABLE ground_schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    groundID INT NOT NULL,
     schedule_date DATE NOT NULL,
    time_slot_id INT NOT NULL,

    FOREIGN KEY (groundID) REFERENCES grounds(groundID),
    FOREIGN KEY (time_slot_id) REFERENCES time_slots(timeID),

    UNIQUE (groundID, schedule_date, time_slot_id)
);



-- BẢNG ĐẶT SÂN 
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ground_schedules_id INT NOT NULL,
    userID INT NOT NULL,
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ground_schedules_id) REFERENCES ground_schedules(id) ON DELETE CASCADE,
    FOREIGN KEY (userID) REFERENCES users(userID) ON DELETE CASCADE
);

