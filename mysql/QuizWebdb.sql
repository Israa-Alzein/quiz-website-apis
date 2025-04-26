create database QuizWebdb;

USE QuizWebdb;

CREATE TABLE QuizWebdb.Admin (
    Admin_ID int NOT NULL AUTO_INCREMENT,
    Email varchar(255) NOT NULL,
    Username varchar(255),
    Password varchar(255),
    PRIMARY KEY (Admin_ID)
);

CREATE TABLE QuizWebdb.User (
    User_ID int NOT NULL AUTO_INCREMENT,
    Email varchar(255) NOT NULL,
    Username varchar(255),
    Password varchar(255),
    PRIMARY KEY (User_ID)
);

CREATE TABLE QuizWebdb.Quiz (
    Quiz_ID int NOT NULL AUTO_INCREMENT,
    Title varchar(255) NOT NULL,
    Description varchar(255),
    Url_picture varchar(255),
    Genre varchar(255),
    PRIMARY KEY (Quiz_ID)
);

CREATE TABLE UserQuizPivot (
    User_ID int,
    Quiz_ID int,
    FOREIGN KEY (User_ID) REFERENCES User(User_ID),
    FOREIGN KEY (Quiz_ID) REFERENCES Quiz(Quiz_ID)
);

CREATE TABLE QuizWebdb.Question (
    Question_ID int NOT NULL AUTO_INCREMENT,
    Quiz_ID int,
    Question_Statement varchar(255) NOT NULL,
    Option_One varchar(255),
    Option_Two varchar(255),
    Option_Three varchar(255),
    Answer varchar(255),
    PRIMARY KEY (Question_ID),
    FOREIGN KEY (Quiz_ID) REFERENCES Quiz(Quiz_ID)
);

CREATE TABLE QuizWebdb.Score (
	Score_ID int NOT NULL AUTO_INCREMENT,
    User_ID int,
    Quiz_ID int,
    Score_nb int,
    PRIMARY KEY (Score_ID),
    FOREIGN KEY (User_ID) REFERENCES User(User_ID),
    FOREIGN KEY (Quiz_ID) REFERENCES Quiz(Quiz_ID)
);

CREATE VIEW Record AS
SELECT 
    s.Score_ID,
    u.Username,
    q.Title AS QuizTitle,
    s.Score_nb AS Score
FROM 
    Score s
JOIN 
    User u ON s.User_ID = u.User_ID
JOIN 
    Quiz q ON s.Quiz_ID = q.Quiz_ID;
    