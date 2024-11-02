CREATE DATABASE IF NOT EXISTS task_management;
USE task_management;

-- Users table
CREATE TABLE IF NOT EXISTS Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

-- Tasks table
CREATE TABLE IF NOT EXISTS Tasks (
    task_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    due_date DATE,
    priority ENUM('low', 'medium', 'high'),
    status ENUM('pending', 'in progress', 'completed'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Categories table
CREATE TABLE IF NOT EXISTS Categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(50) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Settings table
CREATE TABLE IF NOT EXISTS Settings (
    setting_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    notification_preferences ENUM('email', 'SMS', 'push'),
    theme_preferences ENUM('light', 'dark'),
    language VARCHAR(20) DEFAULT 'English',
    timezone VARCHAR(40) DEFAULT 'UTC',
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Reminders table
CREATE TABLE IF NOT EXISTS Reminders (
    reminder_id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT,
    reminder_time TIMESTAMP,
    reminder_type ENUM('one-time', 'recurring'),
    status ENUM('active', 'dismissed'),
    FOREIGN KEY (task_id) REFERENCES Tasks(task_id)
);

-- Analytics table
CREATE TABLE IF NOT EXISTS Analytics (
    analytics_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    date DATE,
    tasks_completed INT DEFAULT 0,
    average_completion_time INT DEFAULT 0,
    daily_focus_time INT DEFAULT 0,
    goals_met INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Collaborations table
CREATE TABLE IF NOT EXISTS Collaborations (
    collaboration_id INT PRIMARY KEY AUTO_INCREMENT,
    task_id INT,
    user_id INT,
    role ENUM('creator', 'contributor'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (task_id) REFERENCES Tasks(task_id),
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Habits table
CREATE TABLE IF NOT EXISTS Habits (
    habit_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    habit_name VARCHAR(50),
    frequency ENUM('daily', 'weekly'),
    start_date DATE,
    end_date DATE,
    current_streak INT DEFAULT 0,
    best_streak INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Notifications table
CREATE TABLE IF NOT EXISTS Notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    type ENUM('task reminder', 'achievement'),
    message TEXT,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    read_status ENUM('read', 'unread') DEFAULT 'unread',
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);

-- Achievements table
CREATE TABLE IF NOT EXISTS Achievements (
    achievement_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(100),
    description TEXT,
    date_earned DATE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
