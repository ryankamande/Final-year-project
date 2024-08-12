CREATE DATABASE garage_management_system;
USE garage_management_system;

CREATE TABLE customer (
    cid INT AUTO_INCREMENT PRIMARY KEY,
    plateNo VARCHAR(30) NOT NULL,
    address VARCHAR(30) NOT NULL,
    name VARCHAR(30) NOT NULL,
    mobile VARCHAR(30) NOT NULL,
    email VARCHAR(40) NOT NULL,
    password VARCHAR(255)
);

CREATE TABLE appointment (
    aid INT AUTO_INCREMENT PRIMARY KEY,
    time TIME NOT NULL,
    date DATE NOT NULL,
    plateNo VARCHAR(30) NOT NULL,
    status VARCHAR(255) DEFAULT 'Pending',
    cid INT,
    FOREIGN KEY (cid) REFERENCES customer(cid)
);

CREATE TABLE employee (
    eid INT AUTO_INCREMENT PRIMARY KEY,
    mobile VARCHAR(11) NOT NULL,
    DOB DATE NOT NULL,
    type VARCHAR(30) NOT NULL DEFAULT 'Mechanic',
    address VARCHAR(30) NOT NULL,
    username VARCHAR(30) NOT NULL,
    password VARCHAR(255),
    name VARCHAR(30) NOT NULL
);

CREATE TABLE job (
    jobId INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(300) NOT NULL,
    status VARCHAR(300) NOT NULL DEFAULT 'in progress',
    aid INT,
    eid INT,
    date DATE DEFAULT '2024-01-01',
    pay VARCHAR(30) DEFAULT 'undone',
    assigned_to INT,
    completion_date DATE,
    cid INT,
    FOREIGN KEY (aid) REFERENCES appointment(aid),
    FOREIGN KEY (eid) REFERENCES employee(eid),
    FOREIGN KEY (cid) REFERENCES customer(cid)
);

CREATE TABLE payment (
    payId INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    total DOUBLE NOT NULL,
    method VARCHAR(50) NOT NULL,
    cid INT NOT NULL,
    plateNo VARCHAR(30) NOT NULL,
    time TIME NOT NULL,
    status VARCHAR(30) NOT NULL,
    FOREIGN KEY (cid) REFERENCES customer(cid)
);
