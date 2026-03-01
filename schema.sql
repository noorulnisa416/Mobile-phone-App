-- =============================================
-- Mobile Phone Repository  –  Database Schema
-- Database : mobile_repo
-- =============================================

CREATE DATABASE IF NOT EXISTS mobile_repo
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE mobile_repo;

-- -----------------------------------------------
-- Table 1: UserLogin  (login credentials)
-- -----------------------------------------------
DROP TABLE IF EXISTS UserLogin;
CREATE TABLE UserLogin (
    id       INT          AUTO_INCREMENT PRIMARY KEY,
    userid   VARCHAR(50)  NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB;

INSERT INTO UserLogin (userid, password) VALUES
    ('itstudent', '12345'),
    ('someone',   'jkl123');

-- -----------------------------------------------
-- Table 2: mobile_phone  (phone repository)
-- Columns: mobile_name | brand | price
-- -----------------------------------------------
DROP TABLE IF EXISTS mobile_phone;
CREATE TABLE mobile_phone (
    id          INT            AUTO_INCREMENT PRIMARY KEY,
    mobile_name VARCHAR(100)   NOT NULL,
    brand       VARCHAR(50)    NOT NULL,
    price       DECIMAL(10, 2) NOT NULL CHECK (price > 0),
    created_at  TIMESTAMP      DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO mobile_phone (mobile_name, brand, price) VALUES
    ('glaxy j6',   'samsung', 16000.00),
    ('mi note 4x', 'mi',      21000.00),
    ('P20 lite',   'huawei',  35000.00),
    ('s6s',        'qmobile',  8000.00);
