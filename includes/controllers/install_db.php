<?php
require_once('../initialize.php');
global $db;

$table['tbl_advertisement'] = "CREATE TABLE IF NOT EXISTS `tbl_advertisement` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`position` int(11) NOT NULL,
	`image` varchar(255) NOT NULL,
	`img_height` int(11) NOT NULL,
	`img_width` int(11) NOT NULL,
	`date_from` date NOT NULL,
	`date_to` date NOT NULL,
	`url_link` varchar(255) NOT NULL,
	`notification` int(11) NOT NULL,
	`notif_status` tinyint(1) NOT NULL DEFAULT '0',
	`mail_to` mediumtext NOT NULL,
	`content` text NOT NULL,
	`remarks` text NOT NULL,
	`mail_status` tinyint(1) NOT NULL DEFAULT '0',
	`status` int(1) NOT NULL DEFAULT '0',
	`added_date` varchar(50) NOT NULL,
	`sortorder` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$table['tbl_articles'] = "CREATE TABLE IF NOT EXISTS `tbl_articles` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`title` varchar(100) NOT NULL,
	`content` text NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`meta_keywords` varchar(250) NOT NULL,
	`meta_description` varchar(250) NOT NULL,
	`type` int(1) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	`sortorder` int(11) NOT NULL,
	`homepage` int(1) NOT NULL DEFAULT '0',
	`image` varchar(50) NOT NULL,
	`date` varchar(100) NOT NULL,
	`category` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";

$table['tbl_bookingchild'] = "CREATE TABLE IF NOT EXISTS `tbl_bookingchild` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`master_id` int(11) NOT NULL,
	`room_type` varchar(200) NOT NULL,
	`room_label` varchar(255) NOT NULL,
	`no_of_room` int(11) NOT NULL,
	`currency` varchar(10) NOT NULL,
	`price` double NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ";

$table['tbl_bookingmaster'] = "CREATE TABLE IF NOT EXISTS `tbl_bookingmaster` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`checkin_date` date NOT NULL,
	`checkout_date` date NOT NULL,
	`totnight` int(11) NOT NULL,
	`first_name` varchar(200) NOT NULL,
	`last_name` varchar(200) NOT NULL,
	`address` varchar(255) NOT NULL,
	`city` varchar(100) NOT NULL,
	`zipcode` varchar(20) NOT NULL,
	`country` varchar(100) NOT NULL,
	`mailaddress` varchar(255) NOT NULL,
	`contact` varchar(100) NOT NULL,
	`booking_date` date NOT NULL,
	`txtnid` varchar(50) NOT NULL,
	`pay_type` int(11) NOT NULL,
	`approved_by` int(11) NOT NULL DEFAULT '0',
	`approved_date` date NOT NULL,
	`added_date` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$table['tbl_configs'] = "CREATE TABLE IF NOT EXISTS `tbl_configs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`sitetitle` varchar(200) NOT NULL,
	`icon_upload` varchar(200) NOT NULL,
	`logo_upload` varchar(200) NOT NULL,
	`sitename` varchar(50) NOT NULL,
	`location_type` tinyint(1) NOT NULL DEFAULT '1',
	`location_map` mediumtext NOT NULL,
	`location_image` varchar(250) NOT NULL,
	`copyright` varchar(200) NOT NULL,
	`site_keywords` varchar(500) NOT NULL,
	`site_description` varchar(500) NOT NULL,
	`google_anlytics` text NOT NULL,
	`template` varchar(100) NOT NULL,
	`admin_template` varchar(100) NOT NULL,
	`headers` text,
	`footer` text,
	`search_box` text,
	`search_result` text,
	`action` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2";

$table['tbl_countries'] = "CREATE TABLE IF NOT EXISTS `tbl_countries` (
	`id` int(3) NOT NULL AUTO_INCREMENT,
	`country_name` varchar(50) NOT NULL,
	`status` tinyint(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=242 ;";

$table['tbl_events'] = "CREATE TABLE IF NOT EXISTS `tbl_events` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(250) NOT NULL,
	`content` text NOT NULL,
	`image` varchar(50) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	`meta_keywords` varchar(250) NOT NULL,
	`meta_description` varchar(250) NOT NULL,
	`event_stdate` date NOT NULL,
	`event_endate` date NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$table['tbl_galleries'] = "CREATE TABLE IF NOT EXISTS `tbl_galleries` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(250) NOT NULL,
	`image` varchar(50) NOT NULL,
	`detail` varchar(200) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`registered` varchar(50) NOT NULL,
	`type` int(1) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_gallery_images'] = "CREATE TABLE IF NOT EXISTS `tbl_gallery_images` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`galleryid` int(11) NOT NULL COMMENT 'is foreign id of galleries.id',
	`title` varchar(200) NOT NULL,
	`image` varchar(50) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`detail` varchar(200) NOT NULL,
	`sortorder` int(11) NOT NULL,
	`registered` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_group_type'] = "CREATE TABLE IF NOT EXISTS `tbl_group_type` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`group_name` varchar(50) NOT NULL,
	`group_type` varchar(20) NOT NULL,
	`authority` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=>Frontend,2=>Personality,3=>Backend,4=>Both',
	`description` tinytext NOT NULL,
	`status` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3";

$table['tbl_logs'] = "CREATE TABLE IF NOT EXISTS `tbl_logs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`action` varchar(50) NOT NULL,
	`registered` varchar(50) NOT NULL,
	`userid` int(11) NOT NULL,
	`user_action` int(11) NOT NULL,
	`ip_track` varchar(20) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_logs_action'] = "CREATE TABLE IF NOT EXISTS `tbl_logs_action` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`icon` varchar(100) NOT NULL,
	`bgcolor` varchar(100) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7";

$table['tbl_menu'] = "CREATE TABLE IF NOT EXISTS `tbl_menu` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`linksrc` varchar(150) NOT NULL,
	`parentOf` int(11) NOT NULL DEFAULT '0',
	`linktype` varchar(10) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	`type` int(1) NOT NULL,
	`icon` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_modules'] = "CREATE TABLE IF NOT EXISTS `tbl_modules` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`parent_id` int(11) NOT NULL DEFAULT '0',
	`name` varchar(50) NOT NULL,
	`link` varchar(50) NOT NULL DEFAULT 'dashboard',
	`mode` varchar(20) NOT NULL,
	`icon_link` varchar(255) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` date NOT NULL,
	`properties` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27";

$table['tbl_news'] = "CREATE TABLE IF NOT EXISTS `tbl_news` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(250) NOT NULL,
	`author` varchar(100) NOT NULL,
	`content` text NOT NULL,
	`news_date` date NOT NULL,
	`archive_date` date DEFAULT NULL,
	`sortorder` int(11) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`image` varchar(50) NOT NULL,
	`source` mediumtext NOT NULL,
	`type` int(1) NOT NULL,
	`meta_keywords` varchar(250) NOT NULL,
	`meta_description` varchar(250) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_package'] = "CREATE TABLE IF NOT EXISTS `tbl_package` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(250) NOT NULL,
	`image` varchar(50) NOT NULL,
	`detail` mediumtext NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	`type` int(1) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6";

$table['tbl_package_sub'] = "CREATE TABLE IF NOT EXISTS `tbl_package_sub` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(255) NOT NULL,
	`detail` mediumtext NOT NULL,
	`facility_title` varchar(200) NOT NULL,
	`facility` text NOT NULL,
	`service_title` varchar(200) NOT NULL,
	`service` text NOT NULL,
	`image` text NOT NULL,
	`content` text NOT NULL,
	`no_rooms` int(11) NOT NULL,
	`currency` varchar(10) NOT NULL,
	`discount` int(11) NOT NULL,
	`people_qnty` int(11) NOT NULL,
	`room_price` tinytext NOT NULL,
	`status` tinyint(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	`type` tinyint(2) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3";

$table['tbl_permission'] = "CREATE TABLE IF NOT EXISTS `tbl_permission` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`type` varchar(5) CHARACTER SET utf8 NOT NULL,
	`group_id` varchar(11) NOT NULL,
	`user_id` int(11) NOT NULL,
	`module_id` varchar(11) NOT NULL,
	`status` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_polloptions'] = "CREATE TABLE IF NOT EXISTS `tbl_polloptions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`pollid` int(11) NOT NULL COMMENT 'foreign id for tbl_polls.id',
	`pollOption` varchar(100) NOT NULL,
	`sortorder` int(11) NOT NULL,
	`hits` int(11) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_polls'] = "CREATE TABLE IF NOT EXISTS `tbl_polls` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`question` varchar(250) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	`sortorder` int(11) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`type` int(1) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_room_price'] = "CREATE TABLE IF NOT EXISTS `tbl_room_price` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`room_id` int(11) NOT NULL,
	`season_id` int(11) NOT NULL,
	`one_person` int(11) NOT NULL,
	`two_person` int(11) NOT NULL,
	`three_person` int(11) NOT NULL,
	`registered` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9";

$table['tbl_season'] = "CREATE TABLE IF NOT EXISTS `tbl_season` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`season` varchar(255) NOT NULL,
	`date_from` date NOT NULL,
	`date_to` date NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` varchar(255) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4";

$table['tbl_slideshow'] = "CREATE TABLE IF NOT EXISTS `tbl_slideshow` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`image` varchar(255) NOT NULL,
	`linksrc` varchar(255) NOT NULL,
	`linktype` tinyint(1) NOT NULL DEFAULT '0',
	`content` longtext NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`added_date` varchar(50) NOT NULL,
	`sortorder` int(11) NOT NULL,
	`type` tinyint(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$table['tbl_slideshows_withouttlist'] = "CREATE TABLE IF NOT EXISTS `tbl_slideshows_withouttlist` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`image` varchar(50) NOT NULL,
	`sortorder` int(11) NOT NULL,
	`registered` varchar(50) NOT NULL,
	`type` int(1) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_social_networking'] = "CREATE TABLE IF NOT EXISTS `tbl_social_networking` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(100) NOT NULL,
	`image` varchar(200) NOT NULL,
	`linksrc` varchar(250) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '1',
	`sortorder` int(11) NOT NULL,
	`registered` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

$table['tbl_testimonial'] = "CREATE TABLE IF NOT EXISTS `tbl_testimonial` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(50) NOT NULL,
	`image` varchar(225) NOT NULL,
	`content` text NOT NULL,
	`sortorder` int(11) NOT NULL,
	`status` int(1) DEFAULT NULL,
	`country` varchar(100) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_touractivity'] = "CREATE TABLE IF NOT EXISTS `tbl_touractivity` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(250) NOT NULL,
	`name` varchar(255) NOT NULL,
	`image` varchar(225) NOT NULL,
	`content` text NOT NULL,
	`sortorder` int(11) NOT NULL,
	`status` int(1) DEFAULT NULL,
	`type` int(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_tourpackage'] = "CREATE TABLE IF NOT EXISTS `tbl_tourpackage` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`title` varchar(250) NOT NULL,
	`name` varchar(255) NOT NULL,
	`image` varchar(225) NOT NULL,
	`content` text NOT NULL,
	`sortorder` int(11) NOT NULL,
	`status` int(1) DEFAULT NULL,
	`country` varchar(100) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_users'] = "CREATE TABLE IF NOT EXISTS `tbl_users` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`first_name` varchar(50) NOT NULL,
	`middle_name` varchar(50) NOT NULL,
	`last_name` varchar(50) NOT NULL,
	`contact` varchar(50) NOT NULL,
	`email` varchar(50) NOT NULL,
	`optional_email` mediumtext NOT NULL,
	`username` varchar(50) NOT NULL,
	`password` varchar(65) NOT NULL,
	`accesskey` varchar(50) NOT NULL,
	`image` varchar(255) NOT NULL,
	`group_id` int(11) NOT NULL,
	`access_code` varchar(255) NOT NULL,
	`facebook_uid` varchar(255) NOT NULL,
	`facebook_accesstoken` varchar(255) NOT NULL,
	`facebook_tokenexpire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	`status` tinyint(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` date NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2";

$table['tbl_user_info'] = "CREATE TABLE IF NOT EXISTS `tbl_user_info` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`person_id` int(11) NOT NULL,
	`email2` varchar(100) NOT NULL,
	`dob` date NOT NULL,
	`zodic_sign` varchar(100) NOT NULL,
	`current_city` tinytext NOT NULL,
	`education` tinytext NOT NULL,
	`home_town` tinytext NOT NULL,
	`phone_res` varchar(30) NOT NULL,
	`phone_office` varchar(30) NOT NULL,
	`mobile_no` varchar(30) NOT NULL,
	`mobile_no2` varchar(30) NOT NULL,
	`children_name` tinytext NOT NULL,
	`pet_name` tinytext NOT NULL,
	`nick_name` varchar(255) NOT NULL,
	`gender` enum('male','female','other') NOT NULL,
	`birth_place` varchar(100) NOT NULL,
	`maritial_status` enum('married','single','divorced') NOT NULL,
	`spouse_name` varchar(100) NOT NULL,
	`publish_spoush_name` tinyint(1) NOT NULL,
	`publish_children_name` varchar(255) NOT NULL,
	`career_start_date` date NOT NULL,
	`facebook_link` varchar(255) NOT NULL,
	`facebook_page` tinytext NOT NULL,
	`twitter_link` tinytext NOT NULL,
	`google_plus` tinytext NOT NULL,
	`linkedin` tinytext NOT NULL,
	`skpye_address` varchar(255) NOT NULL,
	`short_desc` text NOT NULL,
	`website` varchar(255) NOT NULL,
	`other_profession` tinytext NOT NULL,
	`question_set` int(11) NOT NULL,
	`answer_status` tinyint(1) NOT NULL COMMENT '0=>Not finished,1=>finised,2=>ongoing review,3=>complete review,',
	`notification` varchar(50) NOT NULL COMMENT 'notification for answer status complete.',
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_video'] = "CREATE TABLE IF NOT EXISTS `tbl_video` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`source` varchar(200) NOT NULL,
	`url_type` varchar(50) NOT NULL,
	`title` mediumtext NOT NULL,
	`thumb_image` mediumtext NOT NULL,
	`url` varchar(255) NOT NULL,
	`host` varchar(255) NOT NULL,
	`content` text NOT NULL,
	`class` varchar(100) NOT NULL,
	`status` int(1) NOT NULL DEFAULT '0',
	`sortorder` int(11) NOT NULL,
	`added_date` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";

$table['tbl_visitorcounter'] = "CREATE TABLE IF NOT EXISTS `tbl_visitorcounter` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`action` varchar(255) NOT NULL,
	`action_id` int(11) NOT NULL,
	`ip_address` varchar(50) NOT NULL,
	`added_date` date NOT NULL,
	PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1";

// Create tables
foreach($table as $tbl=>$sql) { $db->query($sql)or die(mysqli_error()); }

// Feed Data into the table
$data['tbl_configs'] = "INSERT INTO `tbl_configs` (`id`, `sitetitle`, `icon_upload`, `logo_upload`, `sitename`, `location_type`, `location_map`, `location_image`, `copyright`, `site_keywords`, `site_description`, `google_anlytics`, `template`, `admin_template`, `headers`, `footer`, `search_box`, `search_result`, `action`) 
			VALUES
			(1, 'Synhawk  Version 2.0 ', 'QYpIh_icon.png', 's5IUF_logo.png', 'Synhawk  Version 2.0', 0, '', '', 'Copyrights  © Synhawk  Version 2.0. {year}. All Rights Reserved.', 'Meta keywords', 'Meta description', '', 'web', 'blue', 
			'<header>
				<div class=\"container\">
					<div class=\"row\">
						<div class=\"col-lg-8 col-md-8 col-sm-6\">
							<a href=\"mailto:support@longtail.info\" style=\"color:#816b78;\">support@longtail.info </a>
							<span class=\"glyphicon glyphicon-earphone\"></span> 
							<a href=\"tel:+97761465603\" style=\"color:#816b78;\">+977 61 465603</a>
						</div>
						<div class=\"col-lg-4 col-md-4 col-sm-6 hidden-xs\">
							<div class=\"row\">
								<div class=\"col-lg-6 col-md-7 col-sm-8 col-lg-offset-6 col-md-offset-0 col-sm-offset-6\" style=\"text-align: right;\"></div>
							</div>
						</div>
					</div>
				</div>
			</header>
			<!-- Top Header Section End -->
			<!-- Logo & Navigation Start -->
			<div class=\"navbar navbar-default navbar-static-top\" role=\"navigation\">
				<div class=\"container\">
					<div class=\"navbar-header\">
						<button class=\"navbar-toggle collapsed\" data-target=\".navbar-collapse\" data-toggle=\"collapse\" type=\"button\">
							<span class=\"sr-only\">Toggle navigation </span> 
							<span class=\"icon-bar\"> </span> 
							<span class=\"icon-bar\"> </span> 
							<span class=\"icon-bar\"> </span>
						</button>
						<jcms:module:logos/>
					</div>
					<nav>
						<div class=\"navbar-collapse collapse\">
							<jcms:module:menu/>
						</div>
					</nav>
				</div>
			</div>
			<!-- Logo & Navigation End -->', 

			'<footer>
				<div class=\"footer\">
					<div class=\"container\">
						<div class=\"row\">
							<div class=\"col-md-3 col-sm-3\">
								<h2> ABOUT US <span class=\"triangle-bottomright\"> </span> </h2>
								<div class=\"box\">
									<p> Hotel Peninsula is a deluxe hotel in the heart of Pokhara, Nepal. Modern day facilities greet natural beauty in ample grounds of the spacious and newly designed structure of hotel.
										<a href=\"article-about_us\" class=\"readmore\" style=\"display:block;\">Read More >></a> 
									</p>
								<div class=\"social\">
									<a href=\"https://www.facebook.com/pages/Hotel-Peninsula/465434183525835?ref=br_tf\" target=\"_blank\"><i class=\"fa fa-facebook\"> </i> </a>
									<a href=\"https://www.youtube.com/watch?v=UmxYhiyVLJ0\" target=\"_blank\"><i class=\"fa fa-youtube\"> </i> </a>
								</div>
							</div>
						</div>
					<div class=\"col-md-3 col-sm-3 address\">
						<h2> OUR ADDRESS <span class=\"triangle-bottomright\"> </span> </h2>
						<div class=\"box\">
							<p> Lakeside - 06, Baidam <br /> Pokhara, Nepal </p>
							<p> <strong> Phone: </strong> <a href=\"tel:+97761465603\">+977 61 465603</a><br />
							<strong> Fax: </strong> +977 61 465604<br />
							<strong> Email: </strong><a href=\"mailto:info@peninsulanepal.com\"> info@peninsulanepal.com</a>
							</p>
						</div>
					</div>
					<div class=\"col-md-3 col-sm-3 address\">
						<h2> City Office <span class=\"triangle-bottomright\"> </span> </h2>
						<div class=\"box\">
							<p> Sorhakhutte, <br />Kathmandu, Nepal </p>
							<p> <strong> Phone: </strong> <a href=\"tel:+97714390622\">+977 1 4390622</a><br />
							<strong> Email: </strong><a href=\"mailto:sales@peninsulanepal.com\"> sales@peninsulanepal.com</a></p>
						</div>
					</div>
					<div class=\"col-md-3 col-sm-3\">
						<h2> Our Happy Guests <span class=\"triangle-bottomright\"> </span> </h2>
						<div class=\"box\">
							<jcms:module:testimonial/>
						</div>
					</div>
				</div>
			</div>
			<!-- Footer Section End --> 
			<!-- Footer Copyright Section Start -->
			<div class=\"footer-bottom\">
				<div class=\"container\">
					<div class=\"row\">
						<div class=\"col-md-6 col-sm-6 col-xs-6\">
							<div class=\"box\">
								<p> <jcms:site:copyright/> <a class=\"developed\" href=\"www.longtail.info\" target=\"_blank\">Longtail e-media</a></p>
							</div>
						</div>
						<div class=\"col-md-6 col-sm-6 col-xs-6\">
							<div class=\"box\">
								<jcms:module:bottom_menu/>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>', 'Develop By Amit prajapati', 'Develop By Amit prajapati', 1);";

$data['tbl_countries'] = "INSERT INTO `tbl_countries` (`id`, `country_name`, `status`) 
			VALUES
			(1, 'United States', 1),
			(2, 'Canada', 1),
			(3, 'Mexico', 1),
			(4, 'Afghanistan', 1),
			(5, 'Albania', 1),
			(6, 'Algeria', 1),
			(7, 'Andorra', 1),
			(8, 'Angola', 1),
			(9, 'Anguilla', 1),
			(10, 'Antarctica', 1),
			(11, 'Antigua and Barbuda', 1),
			(12, 'Argentina', 1),
			(13, 'Armenia', 1),
			(14, 'Aruba', 1),
			(15, 'Ascension Island', 1),
			(16, 'Australia', 1),
			(17, 'Austria', 1),
			(18, 'Azerbaijan', 1),
			(19, 'Bahamas', 1),
			(20, 'Bahrain', 1),
			(21, 'Bangladesh', 1),
			(22, 'Barbados', 1),
			(23, 'Belarus', 1),
			(24, 'Belgium', 1),
			(25, 'Belize', 1),
			(26, 'Benin', 1),
			(27, 'Bermuda', 1),
			(28, 'Bhutan', 1),
			(29, 'Bolivia', 1),
			(30, 'Bophuthatswana', 1),
			(31, 'Bosnia-Herzegovina', 1),
			(32, 'Botswana', 1),
			(33, 'Bouvet Island', 1),
			(34, 'Brazil', 1),
			(35, 'British Indian Ocean', 1),
			(36, 'British Virgin Islands', 1),
			(37, 'Brunei Darussalam', 1),
			(38, 'Bulgaria', 1),
			(39, 'Burkina Faso', 1),
			(40, 'Burundi', 1),
			(41, 'Cambodia', 1),
			(42, 'Cameroon', 1),
			(44, 'Cape Verde Island', 1),
			(45, 'Cayman Islands', 1),
			(46, 'Central Africa', 1),
			(47, 'Chad', 1),
			(48, 'Channel Islands', 1),
			(49, 'Chile', 1),
			(50, 'China, Peoples Republic', 1),
			(51, 'Christmas Island', 1),
			(52, 'Cocos (Keeling) Islands', 1),
			(53, 'Colombia', 1),
			(54, 'Comoros Islands', 1),
			(55, 'Congo', 1),
			(56, 'Cook Islands', 1),
			(57, 'Costa Rica', 1),
			(58, 'Croatia', 1),
			(59, 'Cuba', 1),
			(60, 'Cyprus', 1),
			(61, 'Czech Republic', 1),
			(62, 'Denmark', 1),
			(63, 'Djibouti', 1),
			(64, 'Dominica', 1),
			(65, 'Dominican Republic', 1),
			(66, 'Easter Island', 1),
			(67, 'Ecuador', 1),
			(68, 'Egypt', 1),
			(69, 'El Salvador', 1),
			(70, 'England', 1),
			(71, 'Equatorial Guinea', 1),
			(72, 'Estonia', 1),
			(73, 'Ethiopia', 1),
			(74, 'Falkland Islands', 1),
			(75, 'Faeroe Islands', 1),
			(76, 'Fiji', 1),
			(77, 'Finland', 1),
			(78, 'France', 1),
			(79, 'French Guyana', 1),
			(80, 'French Polynesia', 1),
			(81, 'Gabon', 1),
			(82, 'Gambia', 1),
			(83, 'Georgia Republic', 1),
			(84, 'Germany', 1),
			(85, 'Gibraltar', 1),
			(86, 'Greece', 1),
			(87, 'Greenland', 1),
			(88, 'Grenada', 1),
			(89, 'Guadeloupe (French)', 1),
			(90, 'Guatemala', 1),
			(91, 'Guernsey Island', 1),
			(92, 'Guinea Bissau', 1),
			(93, 'Guinea', 1),
			(94, 'Guyana', 1),
			(95, 'Haiti', 1),
			(96, 'Heard and McDonald Isls', 1),
			(97, 'Honduras', 1),
			(98, 'Hong Kong', 1),
			(99, 'Hungary', 1),
			(100, 'Iceland', 1),
			(101, 'India', 1),
			(102, 'Iran', 1),
			(103, 'Iraq', 1),
			(104, 'Ireland', 1),
			(105, 'Isle of Man', 1),
			(106, 'Israel', 1),
			(107, 'Italy', 1),
			(108, 'Ivory Coast', 1),
			(109, 'Jamaica', 1),
			(110, 'Japan', 1),
			(111, 'Jersey Island', 1),
			(112, 'Jordan', 1),
			(113, 'Kazakhstan', 1),
			(114, 'Kenya', 1),
			(115, 'Kiribati', 1),
			(116, 'Kuwait', 1),
			(117, 'Laos', 1),
			(118, 'Latvia', 1),
			(119, 'Lebanon', 1),
			(120, 'Lesotho', 1),
			(121, 'Liberia', 1),
			(122, 'Libya', 1),
			(123, 'Liechtenstein', 1),
			(124, 'Lithuania', 1),
			(125, 'Luxembourg', 1),
			(126, 'Macao', 1),
			(127, 'Macedonia', 1),
			(128, 'Madagascar', 1),
			(129, 'Malawi', 1),
			(130, 'Malaysia', 1),
			(131, 'Maldives', 1),
			(132, 'Mali', 1),
			(133, 'Malta', 1),
			(134, 'Martinique (French)', 1),
			(135, 'Mauritania', 1),
			(136, 'Mauritius', 1),
			(137, 'Mayotte', 1),
			(139, 'Micronesia', 1),
			(140, 'Moldavia', 1),
			(141, 'Monaco', 1),
			(142, 'Mongolia', 1),
			(143, 'Montenegro', 1),
			(144, 'Montserrat', 1),
			(145, 'Morocco', 1),
			(146, 'Mozambique', 1),
			(147, 'Myanmar', 1),
			(148, 'Namibia', 1),
			(149, 'Nauru', 1),
			(150, 'Nepal', 1),
			(151, 'Netherlands Antilles', 1),
			(152, 'Netherlands', 1),
			(153, 'New Caledonia (French)', 1),
			(154, 'New Zealand', 1),
			(155, 'Nicaragua', 1),
			(156, 'Niger', 1),
			(157, 'Niue', 1),
			(158, 'Norfolk Island', 1),
			(159, 'North Korea', 1),
			(160, 'Northern Ireland', 1),
			(161, 'Norway', 1),
			(162, 'Oman', 1),
			(163, 'Pakistan', 1),
			(164, 'Panama', 1),
			(165, 'Papua New Guinea', 1),
			(166, 'Paraguay', 1),
			(167, 'Peru', 1),
			(168, 'Philippines', 1),
			(169, 'Pitcairn Island', 1),
			(170, 'Poland', 1),
			(171, 'Polynesia (French)', 1),
			(172, 'Portugal', 1),
			(173, 'Qatar', 1),
			(174, 'Reunion Island', 1),
			(175, 'Romania', 1),
			(176, 'Russia', 1),
			(177, 'Rwanda', 1),
			(178, 'S.Georgia Sandwich Isls', 1),
			(179, 'Sao Tome, Principe', 1),
			(180, 'San Marino', 1),
			(181, 'Saudi Arabia', 1),
			(182, 'Scotland', 1),
			(183, 'Senegal', 1),
			(184, 'Serbia', 1),
			(185, 'Seychelles', 1),
			(186, 'Shetland', 1),
			(187, 'Sierra Leone', 1),
			(188, 'Singapore', 1),
			(189, 'Slovak Republic', 1),
			(190, 'Slovenia', 1),
			(191, 'Solomon Islands', 1),
			(192, 'Somalia', 1),
			(193, 'South Africa', 1),
			(194, 'South Korea', 1),
			(195, 'Spain', 1),
			(196, 'Sri Lanka', 1),
			(197, 'St. Helena', 1),
			(198, 'St. Lucia', 1),
			(199, 'St. Pierre Miquelon', 1),
			(200, 'St. Martins', 1),
			(201, 'St. Kitts Nevis Anguilla', 1),
			(202, 'St. Vincent Grenadines', 1),
			(203, 'Sudan', 1),
			(204, 'Suriname', 1),
			(205, 'Svalbard Jan Mayen', 1),
			(206, 'Swaziland', 1),
			(207, 'Sweden', 1),
			(208, 'Switzerland', 1),
			(209, 'Syria', 1),
			(210, 'Tajikistan', 1),
			(211, 'Taiwan', 1),
			(212, 'Tahiti', 1),
			(213, 'Tanzania', 1),
			(214, 'Thailand', 1),
			(215, 'Togo', 1),
			(216, 'Tokelau', 1),
			(217, 'Tonga', 1),
			(218, 'Trinidad and Tobago', 1),
			(219, 'Tunisia', 1),
			(220, 'Turkmenistan', 1),
			(221, 'Turks and Caicos Isls', 1),
			(222, 'Tuvalu', 1),
			(223, 'Uganda', 1),
			(224, 'Ukraine', 1),
			(225, 'United Arab Emirates', 1),
			(226, 'Uruguay', 1),
			(227, 'Uzbekistan', 1),
			(228, 'Vanuatu', 1),
			(229, 'Vatican City State', 1),
			(230, 'Venezuela', 1),
			(231, 'Vietnam', 1),
			(232, 'Virgin Islands (Brit,1)', 1),
			(233, 'Wales', 1),
			(234, 'Wallis Futuna Islands', 1),
			(235, 'Western Sahara', 1),
			(236, 'Western Samoa', 1),
			(237, 'Yemen', 1),
			(238, 'Yugoslavia', 1),
			(239, 'Zaire', 1),
			(240, 'Zambia', 1),
			(241, 'Zimbabwe', 1);";

$data['tbl_logs_action'] = "INSERT INTO `tbl_logs_action` (`id`, `title`, `icon`, `bgcolor`) 
			VALUES
			(1, 'Login', 'icon-sign-in', 'bg-blue'),
			(2, 'Logout', 'icon-sign-out', 'primary-bg'),
			(3, 'Add', 'icon-plus-circle', 'bg-green'),
			(4, 'Edit', 'icon-edit', 'bg-blue-alt'),
			(5, 'Copy', 'icon-copy', 'ui-state-default'),
			(6, 'Delete', 'icon-clock-os-circle', 'bg-red');";

$data['tbl_modules'] = "INSERT INTO `tbl_modules` (`id`, `parent_id`, `name`, `link`, `mode`, `icon_link`, `status`, `sortorder`, `added_date`, `properties`) 
			VALUES
			(1, 0, 'User Mgmt', 'user/list', 'user', 'icon-users', 1, 1, '0000-00-00', ''),
			(2, 0, 'Menu Mgmt', 'menu/list', 'menu', 'icon-list', 1, 2, '0000-00-00', 'a:1:{s:5:\"level\";s:1:\"4\";}'),
			(3, 0, 'Articles Mgmt', 'articles/list', 'articles', 'icon-adn', 1, 3, '0000-00-00', 'a:2:{s:8:\"imgwidth\";s:3:\"400\";s:9:\"imgheight\";s:3:\"350\";}'),
			(4, 0, 'Slideshow Mgmt', 'slideshow/list', 'slideshow', 'icon-film', 1, 4, '0000-00-00', 'a:2:{s:8:\"imgwidth\";s:4:\"1400\";s:9:\"imgheight\";s:3:\"680\";}'),
			(5, 0, 'Gallery Mgmt', 'gallery/list', 'gallery', 'icon-picture-o', 1, 5, '0000-00-00', 'a:4:{s:8:\"imgwidth\";s:3:\"800\";s:9:\"imgheight\";s:3:\"600\";s:9:\"simgwidth\";s:3:\"400\";s:10:\"simgheight\";s:3:\"350\";}'),
			(6, 0, 'News Mgmt', 'news/list', 'news', 'icon-list-alt', 0, 6, '0000-00-00', 'a:2:{s:8:\"imgwidth\";s:3:\"300\";s:9:\"imgheight\";s:3:\"300\";}'),
			(7, 0, 'Event Mgmt', 'events/list', 'events', 'icon-bullhorn', 0, 7, '0000-00-00', ''),
			(8, 0, 'Advertisement Mgmt', 'advertisement/list', 'advertisement', 'icon-indent', 0, 10, '0000-00-00', 'a:8:{s:9:\"limgwidth\";s:3:\"100\";s:10:\"limgheight\";s:3:\"200\";s:9:\"timgwidth\";s:3:\"300\";s:10:\"timgheight\";s:3:\"400\";s:9:\"rimgwidth\";s:3:\"500\";s:10:\"rimgheight\";s:3:\"600\";s:9:\"bimgwidth\";s:3:\"700\";s:10:\"bimgheight\";s:3:\"800\";}'),
			(9, 0, 'Video Mgmt', 'video/list', 'video', 'icon-hdd-o', 1, 9, '0000-00-00', ''),
			(10, 0, 'Poll Mgmt', 'poll/list', 'poll', 'icon-exchange', 0, 13, '0000-00-00', ''),
			(11, 0, 'Social Link Mgmt', 'social/list', 'social', 'icon-google-plus', 1, 11, '0000-00-00', 'a:2:{s:8:\"imgwidth\";s:2:\"40\";s:9:\"imgheight\";s:2:\"40\";}'),
			(12, 0, 'Setting Mgmt', 'setting/list', 'settings', 'icon-gear', 1, 12, '0000-00-00', ''),
			(13, 12, 'Preference Mgmt', 'preference/list', 'preference', 'icon-gear', 1, 1, '0000-00-00', 'a:4:{s:8:\"imgwidth\";s:2:\"50\";s:9:\"imgheight\";s:2:\"50\";s:9:\"simgwidth\";s:3:\"125\";s:10:\"simgheight\";s:2:\"80\";}'),
			(14, 12, 'Office Location', 'location/list', 'location', 'icon-gear', 1, 2, '0000-00-00', ''),
			(15, 12, 'Modules Mgmt', 'module/list', 'module', 'icon-gear', 1, 3, '0000-00-00', ''),
			(16, 12, 'Properties Mgmt', 'properties/list', 'properties', 'icon-gear', 1, 4, '0000-00-00', ''),
			(18, 0, 'Testimonial', 'testimonial/list', 'testimonial', 'icon-list-alt', 1, 8, '0000-00-00', 'a:2:{s:8:\"imgwidth\";s:3:\"200\";s:9:\"imgheight\";s:3:\"200\";}'),
			(19, 0, 'Package Mgmt', '', '', 'icon-bullhorn', 1, 4, '2014-12-08', ''),
			(20, 0, 'Tour Package Mgmt', 'tourpackage/list', 'tourpackage', 'icon-briefcase', 0, 6, '2015-02-11', 'a:2:{s:8:\"imgwidth\";s:3:\"599\";s:9:\"imgheight\";s:3:\"300\";}'),
			(21, 0, 'Tour Activity Mgmt 	', 'touractivity/list', 'touractivity', 'icon-truck', 0, 7, '2015-02-11', 'a:2:{s:8:\"imgwidth\";s:3:\"600\";s:9:\"imgheight\";s:3:\"300\";}'),
			(24, 19, 'Season Mgmt', 'season/list', 'season', 'icon-gear', 1, 1, '2015-03-16', ''),
			(26, 19, 'Package Mgmt', 'package/list', 'package', 'icon-gear', 1, 2, '2015-03-16', '');";

$data['tbl_package'] = "INSERT INTO `tbl_package` (`id`, `title`, `image`, `detail`, `status`, `sortorder`, `added_date`, `type`) 
			VALUES
			(1, 'Accommodation', 'PBKGy_accommodiation.jpg', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.</p>\r\n', 0, 3, '2014-12-10 15:58:57', 1),
			(2, 'Conference Hall', 'Gql1A_hall.jpg', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.</p>\r\n', 1, 2, '2014-12-10 15:59:31', 0),
			(4, 'Restaurant', 'Doa1i_restro.jpg', '<div>\r\n	Hotel Peninsula is proud to offer finely nuanced cuisine served for every palate and preference. From casual, all-day eateries to formal fine dining guests can look forward to authenticity, excellent service, gourmet cuisine and a warm and welcoming ambience. Every eating experience at Hotel Peninsula defines itself by authenticity, admirable service, world-class cuisine and an amenable, warm and welcoming ambience. We offer multi-cuisine menu, breakfast buffet, lunch and dinner. Our 64 pax capacity restaurant offers majestic view of spacious garden as well as Peace Stupa.</div>\r\n<div>\r\n	&nbsp;</div>\r\n<div>\r\n	On top, we have a balcony caf&eacute; where you can sip coffee enjoying cold breeze of the Himalayas or simply enjoy the whole day sunbathing.<span class=\"Apple-tab-span\" style=\"white-space:pre\"> </span>&nbsp;&nbsp;</div>\r\n', 1, 1, '2015-02-25 10:49:07', 0),
			(5, 'SPA', '2qZLc_spa.jpg', '<p>\r\n	Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et.</p>\r\n', 1, 0, '2015-02-25 10:57:32', 0);";

$data['tbl_package_sub'] = "INSERT INTO `tbl_package_sub` (`id`, `title`, `detail`, `facility_title`, `facility`, `service_title`, `service`, `image`, `content`, `no_rooms`, `currency`, `discount`, `people_qnty`, `room_price`, `status`, `sortorder`, `added_date`, `type`) 
			VALUES
			(1, 'New Room', '', 'Facility Title', '[\"Facility Name\"]', 'Service Title', '[\"Service Name\"]', '[\"EHVVz_img.jpg\"]', '<p>\r\n	This is testing hai ta.</p>\r\n', 4, 'USD', 0, 3, '', 1, 1, '2015-03-23 11:39:22', 1),
			(2, 'Second Room', '', 'Facility Title', '[\"Facility Name\"]', 'Service Title', '[\"Service Name\"]', '[\"b7EsG_nma.png\"]', '<p>\r\n	This is second room</p>\r\n', 3, 'USD', 0, 2, '[[\"400\",\"300\",\"\"],[\"300\",\"240\",\"\"],[\"200\",\"100\",\"\"],[\"100\",\"50\",\"\"]]', 1, 0, '2015-03-23 14:27:04', 1);";

$data['tbl_room_price'] = "INSERT INTO `tbl_room_price` (`id`, `room_id`, `season_id`, `one_person`, `two_person`, `three_person`, `registered`) 
			VALUES
			(1, 1, 0, 100, 200, 300, '2015-03-23 11:39:23'),
			(2, 1, 1, 150, 250, 350, '2015-03-23 11:39:23'),
			(3, 1, 2, 175, 275, 375, '2015-03-23 11:39:23'),
			(4, 1, 3, 125, 225, 325, '2015-03-23 11:39:23'),
			(5, 2, 0, 400, 300, 0, '2015-03-23 14:27:04'),
			(6, 2, 1, 300, 200, 0, '2015-03-23 14:27:04'),
			(7, 2, 2, 200, 150, 0, '2015-03-23 14:27:04'),
			(8, 2, 3, 100, 225, 0, '2015-03-23 14:27:04');";

$data['tbl_season'] = "INSERT INTO `tbl_season` (`id`, `season`, `date_from`, `date_to`, `status`, `sortorder`, `added_date`) 
			VALUES
			(1, 'Summer', '2015-01-01', '2015-03-31', 1, 1, '2015-03-16 16:44:47'),
			(2, 'Spring', '2015-04-01', '2015-06-30', 1, 2, '2015-03-16 18:22:16'),
			(3, 'Autom', '2015-08-01', '2015-10-31', 1, 3, '2015-03-16 18:23:05');";

$data['tbl_group_type'] = "INSERT INTO `tbl_group_type` (`id`, `group_name`, `group_type`, `authority`, `description`, `status`) 
			VALUES
			(1, 'Administrator', '1', 1, '', 1);";

$data['tbl_users'] = "INSERT INTO `tbl_users` (`id`, `first_name`, `middle_name`, `last_name`, `contact`, `email`, `optional_email`, `username`, `password`, `accesskey`, `image`, `group_id`, `access_code`, `facebook_uid`, `facebook_accesstoken`, `facebook_tokenexpire`, `status`, `sortorder`, `added_date`) 
			VALUES
			(1, 'longtail-e-medai', '', '', '', 'info@longtail.info', 'info@longtail.info;support@longtail.info', 'admin', '32b9da145699ea9058dd7d6669e6bcc5', 'cRaYKbsAmXVzDUToFee32GiYR', '', 1, 'jx3PtXqKso', '', '', '2015-04-08 23:30:04', 1, 1, '2014-03-26');";
//populate
foreach($data as $tbl=>$sql) { $db->query($sql) or die($sql); }
echo "<script language='javascript'>window.location.href = '../../apanel/index.php';</script>";