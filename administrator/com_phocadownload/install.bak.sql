-- -------------------------------------------------------------------- --
-- Phoca Download manual installation                                   --
-- -------------------------------------------------------------------- --
-- See documentation on http://www.phoca.cz/                            --
--                                                                      --
-- Change all prefixes #__ to prefix which is set in your Joomla! site  --
-- (e.g. from #__phocadownload to jos_phocadownload)                    --
-- Run this SQL queries in your database tool, e.g. in phpMyAdmin       --
-- If you have questions, just ask in Phoca Forum                       --
-- http://www.phoca.cz/forum/                                           --
-- -------------------------------------------------------------------- --

DROP TABLE IF EXISTS `#__phocadownload_categories`;
CREATE TABLE `#__phocadownload_categories` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default 0,
  `section` int(11) NOT NULL default 0,
  `title` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `image` varchar(255) NOT NULL default '',
  `image_position` varchar(30) NOT NULL default '',
  `description` text,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `editor` varchar(50) default NULL,
  `ordering` int(11) NOT NULL default '0',
  `access` int(11) unsigned NOT NULL default '0',
  `uploaduserid` text,
  `accessuserid` text,
  `deleteuserid` text,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `count` int(11) NOT NULL default '0',
  `hits` int(11) NOT NULL default '0',
  `params` text,
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `cat_idx` (`section`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`)
) default CHARSET=utf8;

-- DROP TABLE IF EXISTS `#__phocadownload_sections`;
-- CREATE TABLE `#__phocadownload_sections` (
--   `id` int(11) NOT NULL auto_increment,
--   `title` varchar(255) NOT NULL default '',
--   `name` varchar(255) NOT NULL default '',
--   `alias` varchar(255) NOT NULL default '',
--   `image` text,
--   `scope` varchar(50) NOT NULL default '',
--   `image_position` varchar(30) NOT NULL default '',
--   `description` text,
--   `published` tinyint(1) NOT NULL default '0',
--   `checked_out` int(11) unsigned NOT NULL default '0',
--   `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
--   `ordering` int(11) NOT NULL default '0',
--   `access` int(11) unsigned NOT NULL default '0',
--   `date` datetime NOT NULL default '0000-00-00 00:00:00',
--   `count` int(11) NOT NULL default '0',
--   `params` text,
--   `metakey` text,
--   `metadesc` text,
--  PRIMARY KEY  (`id`),
--   KEY `idx_scope` (`scope`)
-- ) TYPE=MyISAM CHARACTER SET `utf8`;

DROP TABLE IF EXISTS `#__phocadownload`;
CREATE TABLE `#__phocadownload` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `catid` int(11) NOT NULL default '0',
  `sectionid` int(11) NOT NULL default '0',
  `sid` int(11) NOT NULL default '0',
  `owner_id` int(11) NOT NULL default 0,
  `title` varchar(250) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `filename` varchar(250) NOT NULL default '',
  `filename_play` varchar(250) NOT NULL default '',
  `filename_preview` varchar(250) NOT NULL default '',
  `filesize` int(11) NOT NULL default 0,
  `author` varchar(255) NOT NULL default '',
  `author_email` varchar(255) NOT NULL default '',
  `author_url` varchar(255) NOT NULL default '',
  `license` varchar(255) NOT NULL default '',
  `license_url` varchar(255) NOT NULL default '',
  `image_filename` varchar(255) NOT NULL default '',
  `image_filename_spec1` varchar(255) NOT NULL default '',
  `image_filename_spec2` varchar(255) NOT NULL default '',
  `image_download` varchar(255) NOT NULL default '',
  `video_filename` varchar(255) NOT NULL default '',
  `link_external` varchar(255) NOT NULL default '',
  `mirror1link` varchar(255) NOT NULL default '',
  `mirror1title` varchar(255) NOT NULL default '',
  `mirror1target` varchar(10) NOT NULL default '',
  `mirror2link` varchar(255) NOT NULL default '',
  `mirror2title` varchar(255) NOT NULL default '',
  `mirror2target` varchar(10) NOT NULL default '',
  `description` text,
  `features` text,
  `changelog` text,
  `notes` text,
  `userid` int(11) NOT NULL default '0',
  `version` varchar(255) NOT NULL default '',
  `directlink` tinyint(1) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL default '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL default '0000-00-00 00:00:00',
  `hits` int(11) NOT NULL default '0',
  `textonly` tinyint(1) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `approved` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `access` int(11) unsigned NOT NULL default '0',
  `confirm_license` int(11) NOT NULL default '0',
  `unaccessible_file` int(11) NOT NULL default '0',
  `params` text,
  `metakey` text,
  `metadesc` text,
  `metadata` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `catid` (`catid`,`published`)
) default CHARSET=utf8;

-- DROP TABLE IF EXISTS `#__phocadownload_settings`;
-- CREATE TABLE `#__phocadownload_settings` (
--   `id` int(11) unsigned NOT NULL auto_increment,
--   `title` varchar(250) NOT NULL default '',
--   `value` text,
--   `values` text,
--   `type` varchar(50) NOT NULL default '',
--   PRIMARY KEY  (`id`)
-- ) TYPE=MyISAM CHARACTER SET `utf8`;

-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'download_folder', 'phocadownload','', 'text');
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'allowed_file_types', '{hqx=application/mac-binhex40}
-- {cpt=application/mac-compactpro}
-- {csv=text/x-comma-separated-values}
-- {bin=application/macbinary}
-- {dms=application/octet-stream}
-- {lha=application/octet-stream}
-- {lzh=application/octet-stream}
-- {exe=application/octet-stream}
-- {class=application/octet-stream}
-- {psd=application/x-photoshop}
-- {so=application/octet-stream}
-- {sea=application/octet-stream}
-- {dll=application/octet-stream}
-- {oda=application/oda}
-- {pdf=application/pdf}
-- {ai=application/postscript}
-- {eps=application/postscript}
-- {ps=application/postscript}
-- {smi=application/smil}
-- {smil=application/smil}
-- {mif=application/vnd.mif}
-- {xls=application/vnd.ms-excel{),
-- {ppt=application/powerpoint}
-- {wbxml=application/wbxml}
-- {wmlc=application/wmlc}
-- {dcr=application/x-director}
-- {dir=application/x-director}
-- {dxr=application/x-director}
-- {dvi=application/x-dvi}
-- {gtar=application/x-gtar}
-- {gz=application/x-gzip}
-- {php=application/x-httpd-php}
-- {php4=application/x-httpd-php}
-- {php3=application/x-httpd-php}
-- {phtml=application/x-httpd-php}
-- {phps=application/x-httpd-php-source}
-- {js=application/x-javascript}
-- {swf=application/x-shockwave-flash}
-- {sit=application/x-stuffit}
-- {tar=application/x-tar}
-- {tgz=application/x-tar}
-- {xhtml=application/xhtml+xml}
-- {xht=application/xhtml+xml}
-- {zip=application/x-zip}
-- {mid=audio/midi}
-- {midi=audio/midi}
-- {mpga=audio/mpeg}
-- {mp2=audio/mpeg}
-- {mp3=audio/mpeg}
-- {aif=audio/x-aiff}
-- {aiff=audio/x-aiff}
-- {aifc=audio/x-aiff}
-- {ram=audio/x-pn-realaudio}
-- {rm=audio/x-pn-realaudio}
-- {rpm=audio/x-pn-realaudio-plugin}
-- {ra=audio/x-realaudio}
-- {rv=video/vnd.rn-realvideo}
-- {wav=audio/x-wav}
-- {bmp=image/bmp}
-- {gif=image/gif}
-- {jpeg=image/jpeg}
-- {jpg=image/jpeg}
-- {jpe=image/jpeg}
-- {png=image/png}
-- {tiff=image/tiff}
-- {tif=image/tiff}
-- {css=text/css}
-- {html=text/html}
-- {htm=text/html}
-- {shtml=text/html}
-- {txt=text/plain}
-- {text=text/plain}
-- {log=text/plain}
-- {rtx=text/richtext}
-- {rtf=text/rtf}
-- {xml=text/xml}
-- {xsl=text/xml}
-- {mpeg=video/mpeg}
-- {mpg=video/mpeg}
-- {mpe=video/mpeg}
-- {qt=video/quicktime}
-- {mov=video/quicktime}
-- {avi=video/x-msvideo}
-- {flv=video/x-flv}
-- {movie=video/x-sgi-movie}
-- {doc=application/msword}
-- {xl=application/excel}
-- {eml=message/rfc822}', '', 'textarea');
-- 
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'disallowed_file_types', '','', 'textarea');
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'upload_maxsize', '3145728','', 'text');
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'enable_flash', 1,'{0=No}{1=Yes}', 'select');
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'absolute_path', '','', 'text');
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'description', '','', 'textareaeditor');
-- INSERT INTO `#__phocadownload_settings` VALUES (null, 'enable_user_statistics', 1,'{0=No}{1=Yes}', 'select');




-- Remove "--" by SQL queries which you want to run in your database (don't remove it by comments)
-- UPDATE ONLY

-- version 1.0.5
-- ALTER TABLE `#__phocadownload` ADD `directlink` tinyint(1) NOT NULL default '0' AFTER `version`;

-- version 1.0.6
DROP TABLE IF EXISTS `#__phocadownload_user_stat`;
CREATE TABLE `#__phocadownload_user_stat` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;


-- version 1.1.0
DROP TABLE IF EXISTS `#__phocadownload_licenses`;
CREATE TABLE `#__phocadownload_licenses` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `description` text,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL default '0',
  `ordering` int(11) NOT NULL default '0',
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;


-- since 2.0.0 RC2
DROP TABLE IF EXISTS `#__phocadownload_file_votes`;
CREATE TABLE `#__phocadownload_file_votes` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default 0,
  `userid` int(11) NOT NULL default 0,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `rating` tinyint(1) NOT NULL default '0',
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `params` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

-- since 2.0.0 RC2
DROP TABLE IF EXISTS `#__phocadownload_file_votes_statistics`;
CREATE TABLE `#__phocadownload_file_votes_statistics` (
  `id` int(11) NOT NULL auto_increment,
  `fileid` int(11) NOT NULL default 0,
  `count` int(11) NOT NULL default '0',
  `average` float(8,6) NOT NULL default '0',
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;


-- since 2.1.0 BETA
DROP TABLE IF EXISTS `#__phocadownload_tags`;
CREATE TABLE `#__phocadownload_tags` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `alias` varchar(255) NOT NULL default '',
  `link_ext` varchar(255) NOT NULL default '',
  `link_cat` int(11) unsigned NOT NULL default '0',
  `description` text,
  `published` tinyint(1) NOT NULL default '0',
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL default '0',
  `params` text,
  `language` char(7) NOT NULL default '',
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

-- since 2.1.0 BETA
DROP TABLE IF EXISTS `#__phocadownload_tags_ref`;
CREATE TABLE `#__phocadownload_tags_ref` (
  `id` SERIAL,
  `fileid` int(11) NOT NULL default 0,
  `tagid` int(11) NOT NULL default 0,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `i_fileid` (`fileid`,`tagid`)
) default CHARSET=utf8;

-- since 2.1.0 BETA
DROP TABLE IF EXISTS `#__phocadownload_layout`;
CREATE TABLE `#__phocadownload_layout` (
  `id` int(11) NOT NULL auto_increment,
  `categories` text,
  `category` text,
  `file` text,
  `checked_out` int(11) unsigned NOT NULL default '0',
  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `params` text,
  PRIMARY KEY  (`id`)
) default CHARSET=utf8;

INSERT INTO `#__phocadownload_layout` (
`id` ,
`categories` ,
`category` ,
`file` ,
`checked_out` ,
`checked_out_time` ,
`params`
)
VALUES (
NULL , '<div class="pd-categoriesbox">
<div class="pd-title">{pdtitle}</div>
{pdsubcategories}
{pdclear}
</div>',

'<div class="pd-filebox">
{pdfiledesctop}
{pdfile}
<div class="pd-buttons">{pdbuttondownload}</div>
<div class="pd-buttons">{pdbuttondetails}</div>
<div class="pd-buttons">{pdbuttonpreview}</div>
<div class="pd-buttons">{pdbuttonplay}</div>
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
{pdfiledescbottom}
<div class="pd-cb"></div>
</div>'

, '<div class="pd-filebox">
{pdimage}
{pdfile}
{pdfilesize}
{pdversion}
{pdlicense}
{pdauthor}
{pdauthoremail}
{pdfiledate}
{pddownloads}
{pddescription}
{pdfeatures}
{pdchangelog}
{pdnotes}
<div class="pd-mirrors">{pdmirrorlink2} {pdmirrorlink1}</div>
<div class="pd-report">{pdreportlink}</div>
<div class="pd-rating">{pdrating}</div>
<div class="pd-tags">{pdtags}</div>
<div class="pd-cb"></div>
</div>' , '0', '0000-00-00 00:00:00', NULL
);



-- 2.0.0 UPDATE ONLY
-- ALTER TABLE `#__phocadownload` ADD `language` char(7) NOT NULL default '' AFTER `params` ;  
-- ALTER TABLE `#__phocadownload_categories` ADD `language` char(7) NOT NULL default '' AFTER `params` ;  
-- ALTER TABLE `#__phocadownload_licenses` ADD `language` char(7) NOT NULL default '' AFTER `ordering` ;  
-- ALTER TABLE `#__phocadownload_user_stat` ADD `language` char(7) NOT NULL default '' AFTER `ordering` ;  
-- ALTER TABLE `#__phocadownload` ADD `metadata` text AFTER `params` ;  
-- ALTER TABLE `#__phocadownload_categories` ADD `metadata` text AFTER `params` ; 
-- ALTER TABLE `#__phocadownload_categories` ADD `hits` int(11) NOT NULL default '0' AFTER `params` ; 

-- 2.0.0 RC2 UPDATE ONLY

-- ALTER TABLE `#__phocadownload` ADD  `mirror1link` varchar(255) NOT NULL default '' AFTER `params` ;
-- ALTER TABLE `#__phocadownload` ADD   `mirror1title` varchar(255) NOT NULL default '' AFTER `params` ;
-- ALTER TABLE `#__phocadownload` ADD   `mirror1target` varchar(10) NOT NULL default '' AFTER `params` ;
-- ALTER TABLE `#__phocadownload` ADD   `mirror2link` varchar(255) NOT NULL default '' AFTER `params` ;
-- ALTER TABLE `#__phocadownload` ADD   `mirror2title` varchar(255) NOT NULL default '' AFTER `params` ;
-- ALTER TABLE `#__phocadownload` ADD   `mirror2target` varchar(10) NOT NULL default '' AFTER `params` ;

-- 2.0.0 STABLE UPDATE ONLY
-- ALTER TABLE `#__phocadownload` ADD   `userid` int(11) NOT NULL default '0' AFTER `description` ;

-- 2.1.0 BETA UPDATE ONLY
-- ALTER TABLE `#__phocadownload` ADD   `features` text AFTER `description` ;
-- ALTER TABLE `#__phocadownload` ADD   `changelog` text AFTER `description` ;
-- ALTER TABLE `#__phocadownload` ADD   `notes` text AFTER `description` ;

-- 2.1.1 UPDATE ONLY
-- ALTER TABLE `#__phocadownload` ADD   `video_filename` varchar(255) NOT NULL default '' AFTER `params` ;