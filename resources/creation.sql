
CREATE DATABASE basicblog;

USE basicblog;

CREATE TABLE commentators (
    commentator_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    password_hash VARCHAR(255) NOT NULL
    );

CREATE TABLE authors (
    author_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(50) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL
    );

CREATE TABLE posts (
    post_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id INT(6),
    title VARCHAR(255),
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    );

CREATE TABLE post_content (
    content_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT(6),
    body TEXT
    );

CREATE TABLE comments (
    comment_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    post_id INT(6),
    commentator_id INT(6),
    body TEXT,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL
    );