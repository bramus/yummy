-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 16, 2010 at 10:36 PM
-- Server version: 5.1.37
-- PHP Version: 5.2.11

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `yummy`
--

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(1024) NOT NULL,
  `title` varchar(1024) NOT NULL,
  `description` text NOT NULL,
  `added` datetime NOT NULL,
  `private` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `private` (`private`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `links_tags`
--

CREATE TABLE `links_tags` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `link_id` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `links_tags` (`link_id`,`tag`),
  KEY `link_id` (`link_id`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `tag` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL,
  `public_qty` int(11) NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
