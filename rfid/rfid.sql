-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-03-11 09:20:01
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `rfid`
--

-- --------------------------------------------------------

--
-- 表的结构 `cat`
--

CREATE TABLE IF NOT EXISTS `cat` (
  `cid` int(16) NOT NULL AUTO_INCREMENT,
  `cname` varchar(64) NOT NULL DEFAULT '""',
  `time` varchar(32) NOT NULL DEFAULT '""',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='钻具类别表' AUTO_INCREMENT=9 ;

--
-- 转存表中的数据 `cat`
--

INSERT INTO `cat` (`cid`, `cname`, `time`) VALUES
(1, '钻杆', '1492864890'),
(2, '钻铤', '1484309238'),
(3, '加重钻杆', '1484358717'),
(4, '方钻杆', '1484309325'),
(5, '转换接头', '1484309359'),
(7, '种类22', '1492864024');

-- --------------------------------------------------------

--
-- 表的结构 `content`
--

CREATE TABLE IF NOT EXISTS `content` (
  `cid` int(8) NOT NULL AUTO_INCREMENT,
  `options` varchar(16) NOT NULL DEFAULT '""',
  `contents` varchar(128) NOT NULL DEFAULT '""',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `content`
--

INSERT INTO `content` (`cid`, `options`, `contents`) VALUES
(1, '维修内容', '修复丝扣#更换公扣#更换母扣'),
(2, '评级', '1#2#3'),
(3, '状态评估', '优秀#良好#一般'),
(4, '使用建议', '防水#防火#防电');

-- --------------------------------------------------------

--
-- 表的结构 `dril`
--

CREATE TABLE IF NOT EXISTS `dril` (
  `did` int(16) NOT NULL AUTO_INCREMENT,
  `cid` varchar(8) NOT NULL DEFAULT '""',
  `pro_factory` varchar(64) NOT NULL DEFAULT '""',
  `pro_time` varchar(32) NOT NULL DEFAULT '""',
  `mat` varchar(32) NOT NULL DEFAULT '""',
  `epc` varchar(64) NOT NULL DEFAULT '""',
  `size` varchar(32) NOT NULL DEFAULT '""',
  `length` varchar(16) NOT NULL DEFAULT '""',
  `screw` varchar(16) NOT NULL DEFAULT '""',
  `number` varchar(64) NOT NULL DEFAULT '""',
  `source` varchar(64) NOT NULL DEFAULT '""',
  `add_time` varchar(32) NOT NULL DEFAULT '""',
  `work_time` varchar(8) NOT NULL DEFAULT '""',
  `firstuse` datetime NOT NULL,
  `work_len` varchar(8) NOT NULL DEFAULT '''''',
  `num` varchar(8) NOT NULL DEFAULT '""',
  `service` int(11) NOT NULL DEFAULT '0',
  `checkname` varchar(32) NOT NULL DEFAULT '""',
  `islive` varchar(8) NOT NULL DEFAULT '否',
  `state` varchar(32) NOT NULL DEFAULT '库存中',
  PRIMARY KEY (`did`),
  KEY `in1` (`add_time`),
  KEY `key1` (`islive`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `dril`
--

INSERT INTO `dril` (`did`, `cid`, `pro_factory`, `pro_time`, `mat`, `epc`, `size`, `length`, `screw`, `number`, `source`, `add_time`, `work_time`, `firstuse`, `work_len`, `num`, `service`, `checkname`, `islive`, `state`) VALUES
(1, '1', '河北秦皇岛', '1491619054', 'N80', 'epc20174693abc', '7', '3', 'L', '20170408', '中航22所', '1491619054', '101', '2017-04-08 10:49:49', '930', '2', 3, 'xzh', '否', '库存中'),
(2, '1', '河北秦皇岛', '1491794365', 'N150', 'epc20173191045abc', '7', '3', 'L', '20170410', '中航22所', '1491794365', '12', '2017-04-04 23:00:09', '410', '2', 3, 'xzh', '否', '已出库');

-- --------------------------------------------------------

--
-- 表的结构 `out`
--

CREATE TABLE IF NOT EXISTS `out` (
  `oid` int(8) NOT NULL AUTO_INCREMENT,
  `did` int(8) NOT NULL,
  `unit` varchar(64) NOT NULL DEFAULT '""',
  `troop` varchar(16) NOT NULL DEFAULT '""',
  `picker` varchar(16) NOT NULL DEFAULT '""',
  `backer` varchar(16) NOT NULL DEFAULT '""',
  `backtime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `operator` varchar(32) NOT NULL DEFAULT '""',
  `picktime` datetime NOT NULL,
  `time` datetime NOT NULL,
  `isback` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`oid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `out`
--

INSERT INTO `out` (`oid`, `did`, `unit`, `troop`, `picker`, `backer`, `backtime`, `operator`, `picktime`, `time`, `isback`) VALUES
(1, 1, '单位2', '01', 'picker', 'xzhHH', '2017-04-10 08:39:24', 'xzhGG', '2017-04-09 15:53:23', '2017-04-09 15:53:23', 1),
(2, 2, '单位2', '01', 'saas', 'saa', '2017-04-22 08:59:33', 'shshss', '2017-04-22 08:48:33', '2017-04-22 08:48:35', 1),
(3, 2, '大叔大叔', '01', 'dddd', '""', '0000-00-00 00:00:00', 'sss', '2017-12-11 09:49:24', '2017-12-11 09:49:26', 0);

-- --------------------------------------------------------

--
-- 表的结构 `property`
--

CREATE TABLE IF NOT EXISTS `property` (
  `pid` int(16) NOT NULL AUTO_INCREMENT,
  `pname` varchar(64) NOT NULL DEFAULT '""',
  `time` varchar(32) NOT NULL DEFAULT '""',
  `nickname` varchar(64) NOT NULL DEFAULT '""',
  `sys` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- 转存表中的数据 `property`
--

INSERT INTO `property` (`pid`, `pname`, `time`, `nickname`, `sys`) VALUES
(1, 'cid', '1484395115', '钻具类型', 1),
(2, 'pro_factory', '1484395100', '生产厂家', 1),
(3, 'pro_time', '1484395106', '生产日期', 1),
(4, 'mat', '1484394862', '钢级(材质)', 1),
(5, 'epc', '1512956285', '钻具编号', 1),
(6, 'size', '1484394940', '型号(英寸)', 1),
(7, 'length', '1484394635', '长度(m)', 1),
(8, 'screw', '1484395079', '丝扣型号', 1),
(9, 'number', '1484393121', '批次编号', 1),
(10, 'source', '1484395056', '采购单位', 1),
(11, 'add_time', '1484395011', '入库验收时间', 1),
(12, 'num', '1484395021', '采购数量', 1),
(13, 'checkname', '1484395002', '入库验收人员', 1),
(14, 'islive', '1483344853', '是否报废', 1),
(18, 'state', '1483237068', '库存状态', 1);

-- --------------------------------------------------------

--
-- 表的结构 `record`
--

CREATE TABLE IF NOT EXISTS `record` (
  `rid` int(8) NOT NULL AUTO_INCREMENT,
  `wid` int(8) NOT NULL,
  `sort` varchar(8) NOT NULL DEFAULT '""',
  `epc` varchar(128) NOT NULL DEFAULT '""',
  `len` varchar(8) NOT NULL DEFAULT '""',
  `time` varchar(16) NOT NULL DEFAULT '""',
  `firstuse` datetime NOT NULL,
  `intime` datetime NOT NULL,
  `outtime` datetime NOT NULL,
  `total_len` varchar(8) NOT NULL DEFAULT '""',
  `total_time` varchar(8) NOT NULL DEFAULT '""',
  `isuse` tinyint(2) NOT NULL DEFAULT '1',
  `isreplace` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `record`
--

INSERT INTO `record` (`rid`, `wid`, `sort`, `epc`, `len`, `time`, `firstuse`, `intime`, `outtime`, `total_len`, `total_time`, `isuse`, `isreplace`) VALUES
(1, 1, '1', 'epc20173191045abc', '""', '""', '0000-00-00 00:00:00', '2017-04-08 10:02:16', '0000-00-00 00:00:00', '""', '""', 0, 0),
(2, 2, '1', 'epc20173191045abc', '200', '1', '0000-00-00 00:00:00', '2017-04-08 10:05:25', '2017-04-08 10:09:48', '""', '""', 0, 1),
(4, 2, '1', 'epc20174693abc', '100', '1', '0000-00-00 00:00:00', '2017-04-08 10:49:49', '2017-04-08 11:43:04', '""', '""', 0, 0),
(5, 5, '1', 'epc20173191045abc', '100', '2', '0000-00-00 00:00:00', '2017-04-08 11:41:39', '2017-04-08 11:42:40', '""', '""', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `rname` varchar(32) NOT NULL DEFAULT '''''',
  `right` varchar(300) NOT NULL DEFAULT '''''',
  `role` varchar(256) NOT NULL DEFAULT '''''',
  `time` datetime NOT NULL,
  PRIMARY KEY (`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`rid`, `rname`, `right`, `role`, `time`) VALUES
(1, '超级管理员', 'a:10:{i:0;s:4:"tool";i:1;s:8:"property";i:2;s:4:"unit";i:3;s:5:"troop";i:4;s:4:"dril";i:5;s:4:"work";i:6;s:7:"service";i:7;s:11:"information";i:8;s:4:"user";i:9;s:4:"role";}', 'a:7:{i:0;s:4:"tool";i:1;s:4:"unit";i:2;s:4:"dril";i:3;s:4:"work";i:4;s:7:"service";i:5;s:11:"information";i:6;s:6:"system";}', '2017-03-18 21:45:22'),
(2, '物资管理员', 'a:1:{i:0;s:4:"dril";}', 'a:1:{i:0;s:4:"dril";}', '2017-04-09 15:37:05');

-- --------------------------------------------------------

--
-- 表的结构 `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `sid` int(8) NOT NULL AUTO_INCREMENT,
  `epc` varchar(64) NOT NULL,
  `did` int(8) NOT NULL,
  `time` datetime NOT NULL,
  `addr` varchar(128) NOT NULL DEFAULT '""',
  `count` int(4) NOT NULL DEFAULT '1',
  `checker` varchar(32) NOT NULL DEFAULT '""',
  `fixer` varchar(32) NOT NULL DEFAULT '""',
  `content` text NOT NULL,
  `class` varchar(64) NOT NULL DEFAULT '""',
  `state` varchar(64) NOT NULL DEFAULT '""',
  `suggest` text NOT NULL,
  `next` datetime NOT NULL,
  `is_new` tinyint(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `service`
--

INSERT INTO `service` (`sid`, `epc`, `did`, `time`, `addr`, `count`, `checker`, `fixer`, `content`, `class`, `state`, `suggest`, `next`, `is_new`) VALUES
(2, 'epc20173191045abc', 2, '2017-04-09 09:52:05', '中航22所', 1, 'xzh', 'xzhGG', '修复丝扣,更换公扣', '2', '良好', '防水,防电', '2017-04-13 09:52:24', 0),
(3, 'epc20173191045abc', 2, '2017-12-11 11:10:46', 'dddd', 1, 'ffdd', 'fddd', '修复丝扣,更换公扣', '1', '优秀', '防水,防火', '2017-12-12 11:10:56', 1);

-- --------------------------------------------------------

--
-- 表的结构 `state`
--

CREATE TABLE IF NOT EXISTS `state` (
  `sid` int(8) NOT NULL AUTO_INCREMENT,
  `task` varchar(16) NOT NULL DEFAULT '''''',
  `epc` text NOT NULL,
  `count` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `state`
--

INSERT INTO `state` (`sid`, `task`, `epc`, `count`) VALUES
(1, '', '''''', 1);

-- --------------------------------------------------------

--
-- 表的结构 `troop`
--

CREATE TABLE IF NOT EXISTS `troop` (
  `tid` int(8) NOT NULL AUTO_INCREMENT,
  `tname` varchar(64) NOT NULL DEFAULT '""',
  `time` datetime NOT NULL,
  `uid` int(8) NOT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `troop`
--

INSERT INTO `troop` (`tid`, `tname`, `time`, `uid`) VALUES
(1, '01', '2017-04-22 21:00:34', 1);

-- --------------------------------------------------------

--
-- 表的结构 `unit`
--

CREATE TABLE IF NOT EXISTS `unit` (
  `uid` int(8) NOT NULL AUTO_INCREMENT,
  `uname` varchar(64) NOT NULL DEFAULT '""',
  `time` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- 转存表中的数据 `unit`
--

INSERT INTO `unit` (`uid`, `uname`, `time`) VALUES
(1, '单位1', '2017-03-19 10:23:16'),
(4, '单位2', '2017-03-26 16:18:13'),
(7, '大叔大叔', '2017-04-22 20:56:08');

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `sid` int(8) NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL DEFAULT '''''',
  `password` varchar(32) NOT NULL DEFAULT '''''',
  `role` int(4) NOT NULL DEFAULT '0',
  `super` tinyint(4) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`sid`, `username`, `password`, `role`, `super`, `time`) VALUES
(1, '2016111675', '12345678', 1, 1, '2017-04-09 23:19:46'),
(5, '2016111676', '123456', 2, 1, '2017-04-09 16:22:05');

-- --------------------------------------------------------

--
-- 表的结构 `work`
--

CREATE TABLE IF NOT EXISTS `work` (
  `wid` int(8) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `w_number` varchar(64) NOT NULL DEFAULT '""',
  `state` int(8) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  PRIMARY KEY (`wid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- 转存表中的数据 `work`
--

INSERT INTO `work` (`wid`, `content`, `w_number`, `state`, `time`) VALUES
(1, 'a:1:{i:1;a:6:{s:3:"cat";s:6:"钻杆";s:3:"len";s:1:"1";s:3:"mat";s:3:"n80";s:4:"size";s:1:"7";s:5:"total";s:3:"100";s:5:"index";i:1;}}', '20170404', 3, '2017-04-04 22:45:21'),
(2, 'a:1:{i:1;a:6:{s:3:"cat";s:6:"钻杆";s:3:"len";s:1:"1";s:3:"mat";s:3:"N80";s:4:"size";s:1:"7";s:5:"total";s:2:"10";s:5:"index";i:1;}}', '20170408', 2, '2017-04-08 10:04:15'),
(5, 'a:1:{i:1;a:6:{s:3:"cat";s:6:"钻杆";s:3:"len";s:1:"1";s:3:"mat";s:3:"N80";s:4:"size";s:1:"7";s:5:"total";s:4:"2222";s:5:"index";i:1;}}', '20170408', 2, '2017-04-08 11:32:37');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
