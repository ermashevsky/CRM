-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Апр 15 2014 г., 09:49
-- Версия сервера: 5.5.35-0ubuntu0.13.10.2
-- Версия PHP: 5.5.3-1ubuntu2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `DialogWebCRM`
--

-- --------------------------------------------------------

--
-- Структура таблицы `cdr`
--

CREATE TABLE IF NOT EXISTS `cdr` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clid` varchar(80) NOT NULL DEFAULT '',
  `src` varchar(80) NOT NULL DEFAULT '',
  `dst` varchar(80) NOT NULL DEFAULT '',
  `channel` varchar(80) NOT NULL DEFAULT '',
  `dstchannel` varchar(80) NOT NULL DEFAULT '',
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `answer` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `duration` int(11) NOT NULL DEFAULT '0',
  `billsec` int(11) NOT NULL DEFAULT '0',
  `disposition` varchar(45) NOT NULL DEFAULT '',
  `cause` int(11) NOT NULL,
  `uniqueid` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `cdr`
--

INSERT INTO `cdr` (`id`, `clid`, `src`, `dst`, `channel`, `dstchannel`, `start`, `answer`, `end`, `duration`, `billsec`, `disposition`, `cause`, `uniqueid`) VALUES
(4, '744146', '744146', '103', 'SIP/trunk-000005f5', 'SIP/103-000005f6', '2014-04-15 09:09:23', '2014-04-15 09:09:32', '2014-04-15 09:09:43', 20, 11, 'ANSWERED', 16, '1397552959.1536'),
(5, '744146', '744146', '102', 'SIP/trunk-000005f7', 'SIP/102-000005f8', '2014-04-15 09:10:30', '0000-00-00 00:00:00', '2014-04-15 09:11:41', 71, 0, 'NO ANSWER', 19, '1397553023.1538'),
(6, '744146', '744146', '103', 'SIP/trunk-000005f9', 'SIP/103-000005fa', '2014-04-15 09:12:42', '0000-00-00 00:00:00', '2014-04-15 09:12:46', 4, 0, 'BUSY', 17, '1397553157.1540'),
(7, '<unknown>', '742743', 'trunk/270214065000', 'SIP/103-000005fb', 'SIP/trunk-000005fc', '2014-04-15 09:13:33', '0000-00-00 00:00:00', '2014-04-15 09:13:38', 5, 0, 'FAILED', 34, '1397553213.1542'),
(8, '<unknown>', '742743', 'trunk/79748', 'SIP/103-000005fd', 'SIP/trunk-000005fe', '2014-04-15 09:21:37', '0000-00-00 00:00:00', '2014-04-15 09:21:37', 0, 0, 'FAILED', 21, '1397553697.1544');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
