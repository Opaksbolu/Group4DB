USE task_management;

INSERT INTO Users (username, email, password) VALUES 
('johndoe', 'johndoe@example.com', 'password123');

INSERT INTO Tasks (user_id, title, description, due_date, priority, status) VALUES 
(1, 'Complete project report', 'Finish the final report by due date.', '2024-11-15', 'high', 'pending');
