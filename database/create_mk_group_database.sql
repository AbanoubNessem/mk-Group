-- Script to create mk_group database and copy all tables from ag_designss
-- Run this script in MySQL/MariaDB

-- Step 1: Create the new database
CREATE DATABASE IF NOT EXISTS `mk_group` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Step 2: Use the new database
USE `mk_group`;

-- Step 3: Copy all tables structure and data from ag_designss
-- Note: Make sure ag_designss database exists and has all tables

-- Copy cache table
CREATE TABLE IF NOT EXISTS `cache` LIKE `ag_designss`.`cache`;
INSERT INTO `cache` SELECT * FROM `ag_designss`.`cache`;

-- Copy cache_locks table
CREATE TABLE IF NOT EXISTS `cache_locks` LIKE `ag_designss`.`cache_locks`;
INSERT INTO `cache_locks` SELECT * FROM `ag_designss`.`cache_locks`;

-- Copy jobs table
CREATE TABLE IF NOT EXISTS `jobs` LIKE `ag_designss`.`jobs`;
INSERT INTO `jobs` SELECT * FROM `ag_designss`.`jobs`;

-- Copy job_batches table
CREATE TABLE IF NOT EXISTS `job_batches` LIKE `ag_designss`.`job_batches`;
INSERT INTO `job_batches` SELECT * FROM `ag_designss`.`job_batches`;

-- Copy failed_jobs table
CREATE TABLE IF NOT EXISTS `failed_jobs` LIKE `ag_designss`.`failed_jobs`;
INSERT INTO `failed_jobs` SELECT * FROM `ag_designss`.`failed_jobs`;

-- Copy users table
CREATE TABLE IF NOT EXISTS `users` LIKE `ag_designss`.`users`;
INSERT INTO `users` SELECT * FROM `ag_designss`.`users`;

-- Copy password_reset_tokens table
CREATE TABLE IF NOT EXISTS `password_reset_tokens` LIKE `ag_designss`.`password_reset_tokens`;
INSERT INTO `password_reset_tokens` SELECT * FROM `ag_designss`.`password_reset_tokens`;

-- Copy sessions table
CREATE TABLE IF NOT EXISTS `sessions` LIKE `ag_designss`.`sessions`;
INSERT INTO `sessions` SELECT * FROM `ag_designss`.`sessions`;

-- Copy projects table
CREATE TABLE IF NOT EXISTS `projects` LIKE `ag_designss`.`projects`;
INSERT INTO `projects` SELECT * FROM `ag_designss`.`projects`;

-- Copy home_sections table
CREATE TABLE IF NOT EXISTS `home_sections` LIKE `ag_designss`.`home_sections`;
INSERT INTO `home_sections` SELECT * FROM `ag_designss`.`home_sections`;

-- Copy services table
CREATE TABLE IF NOT EXISTS `services` LIKE `ag_designss`.`services`;
INSERT INTO `services` SELECT * FROM `ag_designss`.`services`;

-- Copy about_sections table
CREATE TABLE IF NOT EXISTS `about_sections` LIKE `ag_designss`.`about_sections`;
INSERT INTO `about_sections` SELECT * FROM `ag_designss`.`about_sections`;

-- Copy hero_slides table
CREATE TABLE IF NOT EXISTS `hero_slides` LIKE `ag_designss`.`hero_slides`;
INSERT INTO `hero_slides` SELECT * FROM `ag_designss`.`hero_slides`;

-- Copy countries table
CREATE TABLE IF NOT EXISTS `countries` LIKE `ag_designss`.`countries`;
INSERT INTO `countries` SELECT * FROM `ag_designss`.`countries`;

-- Copy contacts table
CREATE TABLE IF NOT EXISTS `contacts` LIKE `ag_designss`.`contacts`;
INSERT INTO `contacts` SELECT * FROM `ag_designss`.`contacts`;

-- Copy contact_infos table
CREATE TABLE IF NOT EXISTS `contact_infos` LIKE `ag_designss`.`contact_infos`;
INSERT INTO `contact_infos` SELECT * FROM `ag_designss`.`contact_infos`;

-- Copy stats table
CREATE TABLE IF NOT EXISTS `stats` LIKE `ag_designss`.`stats`;
INSERT INTO `stats` SELECT * FROM `ag_designss`.`stats`;

-- Copy migrations table
CREATE TABLE IF NOT EXISTS `migrations` LIKE `ag_designss`.`migrations`;
INSERT INTO `migrations` SELECT * FROM `ag_designss`.`migrations`;

-- Success message
SELECT 'Database mk_group created and all tables copied successfully!' AS message;

