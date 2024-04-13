CREATE DATABASE IF NOT EXISTS Comp3005Project;

USE Comp3005Project;

CREATE TABLE IF NOT EXISTS members (
	id SERIAL PRIMARY KEY,
	fname VARCHAR(255),
	lname VARCHAR(255),
	email VARCHAR(255),
	password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS members_health (
	id INT REFERENCES members (id),
	current_weight INT,
	goal_weight INT,
	goal_date DATE
);

CREATE TABLE IF NOT EXISTS workout_history (
	id INT REFERENCES members (id),
	workout_date date
);

CREATE TABLE IF NOT EXISTS trainers (
	id SERIAL PRIMARY KEY,
	email VARCHAR(255),
	password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS trainers_availability (
	id INT REFERENCES trainers (id),
	day VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS fitness_classes (
	id SERIAL PRIMARY KEY,
	day VARCHAR(10),
	room_number INT
);

CREATE TABLE IF NOT EXISTS teaches (
	trainer_id INT REFERENCES trainers (id),
	class_id INT REFERENCES fitness_classes (id)
);

CREATE TABLE IF NOT EXISTS takes (
	class_id INT REFERENCES fitness_classes (id),
	member_id INT REFERENCES members (id)
);

CREATE TABLE IF NOT EXISTS administrators (
	id SERIAL PRIMARY KEY,
	email VARCHAR(255),
	password VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS gym_equipment (
	id SERIAL PRIMARY KEY,
	last_maintenance date
);

CREATE TABLE IF NOT EXISTS bills (
	id SERIAL PRIMARY KEY,
	member_id INT REFERENCES members (id),
	amount DECIMAL
);

ALTER TABLE trainers_availability ADD UNIQUE(id, day);

ALTER TABLE takes ADD UNIQUE(class_id, member_id);

ALTER TABLE members ADD UNIQUE (email);

ALTER TABLE trainers ADD UNIQUE (email);

ALTER TABLE administrators ADD UNIQUE (email);