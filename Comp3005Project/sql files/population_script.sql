INSERT INTO members (fname, lname, email, password) VALUES 
('Fred', 'Blair', 'f.b@email.com', 'pass'),
('Carrie', 'Caldwell', 'c.c@email.com', 'pass'),
('Halle', 'Glover', 'h.g@email.com', 'pass'),
('Emmie', 'Powell', 'e.p@email.com', 'pass'),
('Rafael', 'Wall', 'r.f@email.com', 'pass');

INSERT INTO members_health (id, current_weight, goal_weight, goal_date) VALUES
(1, 250, 200, '2025-04-13'),
(2, 300, 220, '2024-12-31'),
(3, 400, 300, '2024-09-01'),
(4, 210, 175, '2024-10-10'),
(5, 240, 200, '2024-07-07');

INSERT INTO workout_history (id, workout_date) VALUES
(1, '2016-11-23'),
(1, '2017-10-28'),
(1, '2017-12-08'),
(1, '2019-01-12'),
(2, '2017-11-25'),
(2, '2019-02-16'),
(2, '2019-05-11'),
(3, '2021-08-06'),
(3, '2021-10-21'),
(4, '2019-11-21'),
(4, '2020-01-13'),
(4, '2020-01-25'),
(5, '2021-11-22'),
(5, '2023-09-30'),
(5, '2017-11-07'),
(5, '2020-04-11');

INSERT INTO bills (member_id, amount) VALUES
(1, 49.95),
(1, 49.95),
(2, 49.95),
(3, 49.95),
(4, 49.95),
(4, 49.95),
(5, 49.95);

INSERT INTO administrators (email, password) VALUES
('admin1@email.com', 'pass'),
('admin2@email.com', 'pass'),
('admin3@email.com', 'pass');

INSERT INTO gym_equipment (last_maintenance) VALUES
('2017-07-07'),
('2020-08-08'),
('2022-09-09'),
('2023-10-10');

INSERT INTO trainers (email, password) VALUES
('trainer1@email.com', 'pass'),
('trainer2@email.com', 'pass'),
('trainer3@email.com', 'pass'),
('trainer4@email.com', 'pass'),
('trainer5@email.com', 'pass');

INSERT INTO trainers_availability (id, day) VALUES
(1, 'monday'),
(1, 'tuesday'),
(2, 'tuesday'),
(2, 'wednesday'),
(2, 'saturday'),
(2, 'sunday'),
(3, 'friday'),
(3, 'wednesday'),
(4, 'tuesday'),
(4, 'wednesday'),
(4, 'thursday'),
(5, 'friday'),
(5, 'saturday'),
(5, 'sunday');

INSERT INTO fitness_classes (day, room_number) VALUES
('monday', 2),
('tuesday', 5),
('wednesday', 4),
('thursday', 2),
('friday', 3),
('saturday', 7),
('sunday', 1),
('monday', 4),
('tuesday', 10),
('wednesday', 6),
('thursday', 1);

INSERT INTO teaches (trainer_id, class_id) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(3, 6),
(3, 7),
(4, 8),
(4, 9),
(5, 10),
(5, 11);