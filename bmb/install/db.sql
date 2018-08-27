CREATE TABLE `PRENAME_actlogs` (
  `actdetail` text NOT NULL,
  `acter` text NOT NULL,
  `actreason` text NOT NULL,
  `acttime` int(11) NOT NULL DEFAULT '0',
  `forumid` int(11) NOT NULL DEFAULT '0',
  `actioncode` text NOT NULL,
  KEY `acttime` (`acttime`)
);
CREATE TABLE `PRENAME_apclog` (
  `adminid` text NOT NULL,
  `adminpwd` text NOT NULL,
  `actionstatus` text NOT NULL,
  `adminip` text NOT NULL,
  `admintime` text NOT NULL,
  `addtime` int(11) NOT NULL DEFAULT '0',
  KEY `addtime` (`addtime`)
);
CREATE TABLE `PRENAME_beg` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  `beglog` text NOT NULL,
  `giftid` text NOT NULL,
  `begers` mediumint(9) NOT NULL DEFAULT '0',
  `begmoneys` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
);
CREATE TABLE `PRENAME_bmbcode` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `refrom` mediumtext NOT NULL,
  `reto` mediumtext NOT NULL,
  `enable` tinyint(1) NOT NULL DEFAULT '0',
  `codetag` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `desc` tinytext NOT NULL,
  `example` tinytext NOT NULL,
  `nestings` tinyint(1) NOT NULL DEFAULT '0',
  `displayorder` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
);
CREATE TABLE `PRENAME_contacts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `adddate` int(11) NOT NULL DEFAULT '0',
  `owner` mediumint(8) NOT NULL DEFAULT '0',
  `contacts` mediumint(8) NOT NULL DEFAULT '0',
  `conname` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `type` (`type`,`owner`),
  KEY `contacts` (`type`,`owner`,`contacts`)
) ;
CREATE TABLE `PRENAME_emoticons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `emotcode` varchar(30) NOT NULL,
  `emotpack` varchar(30) NOT NULL,
  `packname` tinytext NOT NULL,
  `emotname` tinytext NOT NULL,
  `thumb` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `emotpack` (`emotpack`)
) ;
CREATE TABLE `PRENAME_favorites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `owner` mediumint(8) NOT NULL DEFAULT '0',
  `subscribe` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `tid` (`tid`,`owner`),
  KEY `subscribe` (`tid`,`subscribe`)
) ;
CREATE TABLE `PRENAME_forumdata` (
  `type` text NOT NULL,
  `bbsname` text NOT NULL,
  `cdes` text NOT NULL,
  `id` int(11) unsigned NOT NULL DEFAULT '0',
  `blad` text NOT NULL,
  `forum_icon` text NOT NULL,
  `filename` text NOT NULL,
  `forumpass` text NOT NULL,
  `forum_cid` text NOT NULL,
  `guestpost` text NOT NULL,
  `forum_ford` text NOT NULL,
  `postdontadd` text NOT NULL,
  `spusergroup` text NOT NULL,
  `naviewpost` text NOT NULL,
  `adminlist` text NOT NULL,
  `topicnum` int(11) unsigned NOT NULL DEFAULT '0',
  `replysnum` int(11) unsigned NOT NULL DEFAULT '0',
  `fltitle` text NOT NULL,
  `flposttime` text NOT NULL,
  `flposter` text NOT NULL,
  `flfname` text NOT NULL,
  `showorder` int(11) NOT NULL DEFAULT '0',
  `usergroup` text NOT NULL,
  `caterows` tinyint(2) NOT NULL DEFAULT '0',
  `todayp` int(7) unsigned NOT NULL DEFAULT '0',
  `todaypt` int(11) unsigned NOT NULL DEFAULT '0',
  `pincount` smallint(3) unsigned NOT NULL DEFAULT '0',
  `trashcount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `digestcount` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `jumpurl` tinytext NOT NULL,
  KEY `id` (`id`),
  KEY `showorder` (`showorder`)
);
CREATE TABLE `PRENAME_gueststat` (
  `username` text NOT NULL,
  `timestamp` text,
  `ips` text NOT NULL,
  `actionnum` text NOT NULL,
  `browser` text NOT NULL,
  `os` text NOT NULL,
  `filename` text NOT NULL
);
CREATE TABLE `PRENAME_invite` (
  `invitecode` varchar(9) NOT NULL DEFAULT '',
  `inviter` int(11) NOT NULL DEFAULT '0',
  `targetmail` text NOT NULL,
  `datetime` int(11) NOT NULL DEFAULT '0',
  `accepted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `newmember` varchar(60) NOT NULL DEFAULT '',
  KEY `accepted` (`accepted`),
  KEY `datetime` (`datetime`),
  KEY `inviter` (`inviter`)
);
CREATE TABLE `PRENAME_lastest` (
  `pageid` text NOT NULL,
  `lastreged` text NOT NULL,
  `regednum` int(11) unsigned NOT NULL DEFAULT '0',
  `threadnum` int(11) unsigned NOT NULL DEFAULT '0',
  `postsnum` int(11) unsigned NOT NULL DEFAULT '0',
  `todaynew` int(11) unsigned NOT NULL DEFAULT '0',
  `lasttodaytime` int(11) NOT NULL DEFAULT '0',
  `ydaynew` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `maxnews` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `lastposts` text NOT NULL,
  `lastposter` text NOT NULL,
  `lastpostid` int(11) NOT NULL DEFAULT '0',
  `lastptime` int(11) NOT NULL DEFAULT '0',
  `zytime` text NOT NULL,
  `zypeople` text NOT NULL
);
CREATE TABLE `PRENAME_levels` (
  `id` mediumint(9) NOT NULL DEFAULT '0',
  `maccess` text NOT NULL,
  `fid` int(11) NOT NULL DEFAULT '0'
);
CREATE TABLE `PRENAME_noticefilter` (
  `uid` int(11) NOT NULL,
  `filterrule` text NOT NULL,
  PRIMARY KEY (`uid`)
);
CREATE TABLE `PRENAME_notification` (
  `nid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `senderid` int(11) NOT NULL,
  `sendername` varchar(60) NOT NULL,
  `receiverid` int(11) NOT NULL,
  `ntype` varchar(20) NOT NULL,
  `nvalue` text NOT NULL,
  `timestamp` int(11) NOT NULL,
  `pkey` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nid`),
  UNIQUE KEY `receiverid_2` (`receiverid`,`ntype`,`pkey`),
  KEY `timestamp` (`timestamp`),
  KEY `receiverid` (`receiverid`,`timestamp`)
) ;
CREATE TABLE `PRENAME_oauth` (
  `uid` int(11) NOT NULL,
  `access_token` varchar(100) NOT NULL,
  `uniqueid` varchar(100) NOT NULL,
  `expires_in` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  PRIMARY KEY (`uid`,`type`),
  KEY `uniqueid` (`uniqueid`,`type`)
);
CREATE TABLE `PRENAME_onlinestat` (
  `username` text NOT NULL,
  `onusrid` mediumint(8) NOT NULL DEFAULT '0',
  `timestamp` text,
  `ips` text NOT NULL,
  `actionnum` text NOT NULL,
  `browser` text NOT NULL,
  `os` text NOT NULL,
  `filename` text NOT NULL,
  `ugnum` text NOT NULL,
  `prioption` text NOT NULL
);
CREATE TABLE `PRENAME_polls` (
  `id` int(11) NOT NULL DEFAULT '0',
  `options` text NOT NULL,
  `polluser` text NOT NULL,
  `setting` text NOT NULL,
  `forumid` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`)
);
CREATE TABLE `PRENAME_posts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL DEFAULT '0',
  `articletitle` text NOT NULL,
  `username` text NOT NULL,
  `usrid` mediumint(8) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `usericon` text NOT NULL,
  `options` text NOT NULL,
  `other1` text NOT NULL,
  `other2` text NOT NULL,
  `other3` text NOT NULL,
  `other4` text NOT NULL,
  `other5` text NOT NULL,
  `addin` text NOT NULL,
  `articlecontent` text NOT NULL,
  `timestamp` int(11) NOT NULL DEFAULT '0',
  `forumid` int(11) NOT NULL DEFAULT '0',
  `changtime` int(11) NOT NULL DEFAULT '0',
  `sellbuyer` text NOT NULL,
  `posttrash` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `changtime` (`changtime`),
  KEY `timestamp` (`timestamp`),
  KEY `globaltid` (`tid`,`id`),
  KEY `posttrash` (`posttrash`)
) ;
CREATE TABLE `PRENAME_potlog` (
  `pusername` text NOT NULL,
  `pauthor` text NOT NULL,
  `bymchange` text NOT NULL,
  `pforumid` text NOT NULL,
  `pfilename` text NOT NULL,
  `particle` text NOT NULL,
  `ptimestamp` int(14) DEFAULT NULL,
  KEY `ptimestamp` (`ptimestamp`)
);
CREATE TABLE `PRENAME_primsg` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `belong` varchar(60) NOT NULL DEFAULT '',
  `sendto` varchar(60) NOT NULL DEFAULT '',
  `prtitle` varchar(200) NOT NULL DEFAULT '',
  `prtime` int(11) NOT NULL DEFAULT '0',
  `prcontent` text NOT NULL,
  `prread` tinyint(1) NOT NULL DEFAULT '0',
  `prother` varchar(100) NOT NULL DEFAULT '',
  `prtype` char(1) NOT NULL DEFAULT '',
  `prkeepsnd` tinyint(1) DEFAULT '0',
  `stid` mediumint(9) NOT NULL DEFAULT '0',
  `blid` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `prkeepsnd` (`blid`,`prkeepsnd`,`prtime`),
  KEY `stidtime` (`stid`,`prtime`)
) ;
CREATE TABLE `PRENAME_schedule` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `taskname` tinytext NOT NULL,
  `available` tinyint(4) NOT NULL DEFAULT '0',
  `day` tinyint(4) NOT NULL DEFAULT '0',
  `week` tinyint(4) NOT NULL DEFAULT '0',
  `min` varchar(30) NOT NULL DEFAULT '0',
  `hour` tinyint(4) NOT NULL DEFAULT '0',
  `last` int(11) NOT NULL DEFAULT '0',
  `next` int(11) NOT NULL DEFAULT '0',
  `fname` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `available` (`available`)
) ;
CREATE TABLE `PRENAME_search` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `result` text NOT NULL,
  `bulitdate` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bulitdate` (`bulitdate`)
) ;
CREATE TABLE `PRENAME_shareforum` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `gif` varchar(255) NOT NULL DEFAULT '',
  `des` varchar(255) NOT NULL DEFAULT '',
  `type` char(3) NOT NULL DEFAULT '',
  `showorder` smallint(3) NOT NULL DEFAULT '0',
  KEY `order` (`showorder`)
);
CREATE TABLE `PRENAME_tags` (
  `tagid` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `threads` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `tagname` varchar(100) NOT NULL DEFAULT '',
  `filename` text NOT NULL,
  UNIQUE KEY `tagid` (`tagid`),
  KEY `threads` (`threads`)
) ;
CREATE TABLE `PRENAME_threads` (
  `id` int(11) NOT NULL DEFAULT '0',
  `tid` int(11) NOT NULL DEFAULT '0',
  `toptype` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ttrash` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `lastreply` text NOT NULL,
  `topic` tinytext NOT NULL,
  `forumid` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  `replys` int(11) unsigned NOT NULL DEFAULT '0',
  `changetime` int(11) NOT NULL DEFAULT '0',
  `itsre` tinytext NOT NULL,
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `islock` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `title` text NOT NULL,
  `newdesc` text NOT NULL,
  `author` text NOT NULL,
  `authorid` int(11) NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `face` tinytext NOT NULL,
  `options` text NOT NULL,
  `other1` text NOT NULL,
  `other2` text NOT NULL,
  `other3` text NOT NULL,
  `other4` text NOT NULL,
  `other5` text NOT NULL,
  `statdata` text NOT NULL,
  `addinfo` text NOT NULL,
  `alldata` int(11) NOT NULL DEFAULT '0',
  `replyer` text NOT NULL,
  `ttagname` varchar(100) NOT NULL DEFAULT '',
  `ttagid` varchar(20) NOT NULL DEFAULT '',
  `ordertrash` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `diggcount` int(11) NOT NULL DEFAULT '0',
  KEY `id` (`id`),
  KEY `tid` (`tid`),
  KEY `changetime` (`changetime`),
  KEY `time` (`time`),
  KEY `islock` (`islock`),
  KEY `ordertrash` (`ordertrash`),
  KEY `topindex` (`forumid`,`toptype`,`ttrash`,`changetime`),
  KEY `diggcount` (`diggcount`)
);
CREATE TABLE `PRENAME_ugoptlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `optname` varchar(100) NOT NULL DEFAULT '',
  `opttype` smallint(6) NOT NULL DEFAULT '0',
  `noforum` tinyint(1) NOT NULL DEFAULT '0',
  `nolevel` tinyint(1) NOT NULL DEFAULT '0',
  `noguest` tinyint(1) NOT NULL DEFAULT '0',
  `mustforum` tinyint(1) NOT NULL DEFAULT '0',
  `optid` int(11) NOT NULL DEFAULT '0',
  `optidt` int(11) NOT NULL DEFAULT '0',
  `specmod` mediumint(9) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forumonly` (`noforum`),
  KEY `levelonly` (`nolevel`),
  KEY `noguest` (`noguest`)
) ;
CREATE TABLE `PRENAME_usergroup` (
  `id` int(11) NOT NULL DEFAULT '0',
  `usersets` text NOT NULL,
  `adminsets` text NOT NULL,
  `unshowit` text NOT NULL,
  `showsort` int(11) NOT NULL DEFAULT '0',
  `regwith` text NOT NULL,
  KEY `id` (`id`),
  KEY `showsort` (`showsort`)
);
CREATE TABLE `PRENAME_userlist` (
  `userid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `pwd` varchar(32) NOT NULL DEFAULT '',
  `avarts` text NOT NULL,
  `mailadd` text NOT NULL,
  `qqmsnicq` tinytext NOT NULL,
  `regdate` tinytext NOT NULL,
  `signtext` text NOT NULL,
  `homepage` tinytext NOT NULL,
  `fromwhere` tinytext NOT NULL,
  `desper` text NOT NULL,
  `headtitle` tinytext NOT NULL,
  `lastpost` int(11) NOT NULL DEFAULT '0',
  `postamount` int(11) NOT NULL DEFAULT '0',
  `publicmail` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `mailtype` varchar(4) NOT NULL DEFAULT '',
  `point` int(11) NOT NULL DEFAULT '0',
  `pwdask` tinytext NOT NULL,
  `pwdanswer` varchar(32) NOT NULL DEFAULT '',
  `ugnum` int(11) NOT NULL DEFAULT '0',
  `money` int(11) NOT NULL DEFAULT '0',
  `birthday` tinytext NOT NULL,
  `team` tinytext NOT NULL,
  `sex` tinytext NOT NULL,
  `national` tinytext NOT NULL,
  `lastlogin` int(11) NOT NULL DEFAULT '0',
  `tlastvisit` int(11) NOT NULL DEFAULT '0',
  `deltopic` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `delreply` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `uploadfiletoday` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `lastupload` int(11) NOT NULL DEFAULT '0',
  `foreuser` tinytext NOT NULL,
  `hisipa` varchar(20) NOT NULL DEFAULT '',
  `hisipb` varchar(20) NOT NULL DEFAULT '',
  `hisipc` varchar(20) NOT NULL DEFAULT '',
  `newmess` smallint(3) unsigned NOT NULL DEFAULT '0',
  `baoliu1` text NOT NULL,
  `baoliu2` text NOT NULL,
  `personug` text NOT NULL,
  `digestmount` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `activestatus` tinytext NOT NULL,
  `parusrid` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `pwdrecovery` varchar(5) DEFAULT NULL,
  `salt` varchar(8) NOT NULL,
  `unreadnote` smallint(6) NOT NULL DEFAULT '0',
  `lastreadnote` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userid`),
  KEY `postamount` (`postamount`),
  KEY `point` (`point`),
  KEY `ugnum` (`ugnum`),
  KEY `money` (`money`),
  KEY `username` (`username`),
  KEY `parusrid` (`parusrid`)
) ;
CREATE TABLE `PRENAME_user_add` (
  `uid` int(10) unsigned NOT NULL DEFAULT '0',
  `cover` text NOT NULL,
  PRIMARY KEY (`uid`)
) ;