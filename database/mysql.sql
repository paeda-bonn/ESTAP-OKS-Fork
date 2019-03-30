--
-- Copyright 2013 Amos-Comenius-Gymnasium Bonn <http://www.acg-bonn.de/>
-- See LICENSE.md for licensing information. 
--

DROP TABLE IF EXISTS `appointments`;
DROP TABLE IF EXISTS `time_slots`;
DROP TABLE IF EXISTS `teachers`;
DROP TABLE IF EXISTS `pupils`;
DROP TABLE IF EXISTS `admins`;

CREATE TABLE `pupils`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `login` varchar(64) NOT NULL,
    `password` varchar(128) NOT NULL,
    `first_name` varchar(32) NOT NULL,
    `last_name` varchar(32) NOT NULL,
    `class` varchar(4) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `teachers`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `login` varchar(64) NOT NULL UNIQUE,
    `password` varchar(128) NOT NULL,
    `first_name` varchar(32) NOT NULL,
    `last_name` varchar(32) NOT NULL,
    `gender` enum('f', 'm') NOT NULL,
    `room` varchar(32) NOT NULL,
    `active` tinyint(1) NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `admins`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `login` varchar(64) NOT NULL UNIQUE,
    `password` varchar(128) NOT NULL,
    `first_name` varchar(32) NOT NULL,
    `last_name` varchar(32) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `time_slots`
(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `start_time` time NOT NULL,
    `end_time` time NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `start_time` (`start_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `appointments`(
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `time_slot_id` int(11) NOT NULL,
    `teacher_id` int(11) NOT NULL,
    `pupil_id` int(11) NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `appointments_time_slot` FOREIGN KEY (`time_slot_id`) 
        REFERENCES `time_slots` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `appointments_teacher` FOREIGN KEY (teacher_id) 
        REFERENCES `teachers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `appointments_pupil`FOREIGN KEY (pupil_id) 
        REFERENCES `pupils` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `appointments_teacher_pupil` UNIQUE (`teacher_id`, `pupil_id`),
    CONSTRAINT `appointments_teacher_time_slot` UNIQUE (`teacher_id`, `time_slot_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `admins` values (1, 'admin', '$6$5249e7e24ba8c$6EHRYK58Xxo8ro3LJfhFu8QTzOwq//cX/FvVIwBIrEj/d58ZFukbj//Ul8xByXWfweYxBd4nR8fOWk98DlqxH.', 'First Name', 'Last Name');
