SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 테이블 구조 `board`
--

CREATE TABLE `board` (
  `b_idx` int(11) NOT NULL,
  `bc_code` varchar(50) NOT NULL,
  `b_num` int(11) NOT NULL,
  `b_reply` varchar(3) NOT NULL,
  `m_id` varchar(12) NOT NULL,
  `name` varchar(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `b_cnt` int(11) NOT NULL DEFAULT '0',
  `b_regdate` datetime NOT NULL,
  `s_content` text NOT NULL,
  `c_cnt` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `parent_deleted` tinyint(4) NOT NULL DEFAULT '0',
  `notice` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블 구조 `board_config`
--

CREATE TABLE `board_config` (
  `bc_idx` int(11) NOT NULL,
  `bc_code` varchar(50) NOT NULL,
  `bc_name` varchar(50) NOT NULL,
  `bc_read_level` tinyint(2) NOT NULL DEFAULT '0',
  `bc_write_level` tinyint(2) NOT NULL DEFAULT '0',
  `bc_comment_level` tinyint(2) NOT NULL DEFAULT '0',
  `type` char(20) NOT NULL,
  `indent` tinyint(2) NOT NULL,
  `seq` int(11) NOT NULL,
  `is_group` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블의 덤프 데이터 `board_config`
--

INSERT INTO `board_config` (`bc_idx`, `bc_code`, `bc_name`, `bc_read_level`, `bc_write_level`, `bc_comment_level`, `type`, `indent`, `seq`, `is_group`) VALUES
(1, 'basic', '일반게시판', 0, 1, 1, 'normal', 0, 1, 0),
(2, 'pic', '사진게시판', 0, 1, 1, 'image', 0, 2, 0);

--
-- 테이블 구조 `chat`
--

CREATE TABLE `chat` (
  `id` int(11) NOT NULL,
  `senderId` char(40) NOT NULL,
  `receiverId` char(40) NOT NULL,
  `message` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블 구조 `comment`
--

CREATE TABLE `comment` (
  `c_idx` int(11) NOT NULL,
  `b_idx` int(11) NOT NULL,
  `m_id` varchar(12) NOT NULL,
  `name` varchar(10) NOT NULL,
  `c_pass` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `c_regdate` datetime NOT NULL,
  `cp_idx` int(11) NOT NULL,
  `c_seq` int(11) NOT NULL,
  `s_content` text NOT NULL,
  `p_idx` int(11) NOT NULL,
  `cp_name` char(40) NOT NULL,
  `deleted` int(11) NOT NULL,
  `rereply` TINYINT(2) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블 구조 `file`
--

CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `b_idx` int(11) NOT NULL,
  `filename` varchar(100) NOT NULL,
  `filesize` int(11) NOT NULL,
  `filetype` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블 구조 `member`
--

CREATE TABLE `member` (
  `m_idx` int(11) NOT NULL,
  `m_id` varchar(12) NOT NULL,
  `email` char(40) NOT NULL,
  `m_name` varchar(10) NOT NULL,
  `m_pass` varchar(100) NOT NULL,
  `m_level` tinyint(2) NOT NULL DEFAULT '1',
  `is_login` TINYINT(2) NOT NULL DEFAULT '0',
  `login_session` CHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 테이블 구조 `message`
--

CREATE TABLE `message` (
  `no` int(11) NOT NULL,
  `senderId` char(40) NOT NULL,
  `senderNick` char(40) NOT NULL,
  `receiverId` char(40) NOT NULL,
  `receiverNick` char(40) NOT NULL,
  `content` text NOT NULL,
  `write_time` datetime NOT NULL,
  `read_time` datetime NOT NULL,
  `read_message` tinyint(2) NOT NULL,
  `sender_deleted` tinyint(2) NOT NULL,
  `receiver_deleted` tinyint(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 테이블의 인덱스 `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`b_idx`);

--
-- 테이블의 인덱스 `board_config`
--
ALTER TABLE `board_config`
  ADD PRIMARY KEY (`bc_idx`),
  ADD UNIQUE KEY `bc_code` (`bc_code`);

--
-- 테이블의 인덱스 `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`c_idx`);

--
-- 테이블의 인덱스 `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`);

--
-- 테이블의 인덱스 `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`m_idx`),
  ADD UNIQUE KEY `m_id` (`m_id`);

--
-- 테이블의 인덱스 `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`no`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `board`
--
ALTER TABLE `board`
  MODIFY `b_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 테이블의 AUTO_INCREMENT `board_config`
--
ALTER TABLE `board_config`
  MODIFY `bc_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 테이블의 AUTO_INCREMENT `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 테이블의 AUTO_INCREMENT `comment`
--
ALTER TABLE `comment`
  MODIFY `c_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 테이블의 AUTO_INCREMENT `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 테이블의 AUTO_INCREMENT `member`
--
ALTER TABLE `member`
  MODIFY `m_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- 테이블의 AUTO_INCREMENT `message`
--
ALTER TABLE `message`
  MODIFY `no` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
