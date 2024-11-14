USE task_management;

INSERT INTO Users (username, email, password) VALUES
('testuser', 'testuser@example.com', MD5('password123'));

INSERT INTO Tasks (user_id, title, description, due_date, priority, status) VALUES
(1, 'Sample Task', 'This is a sample task description.', '2024-12-01', 'High', 'Pending');

INSERT INTO Categories (user_id, name, description) VALUES
(1, 'Work', 'Tasks related to work');

INSERT INTO Settings (user_id, notification_preferences, theme_preferences, language, timezone) VALUES
(1, 'Email', 'Dark', 'English', 'UTC');

INSERT INTO Reminders (task_id, reminder_time, reminder_type, status) VALUES
(1, '2024-11-15 10:00:00', 'Email Reminder', 'Active');
