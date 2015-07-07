/*
 * Name: ciauth
 * File: ciauth.sql
 * Path: sql/ciauth.php
 * Author: Glen Barnhardt
 * Company: Barnhardt Enterprises, Inc.
 * Email: glen@barnhardtenterprises.com
 * SiteURL: http://www.ciauth.com
 * GitHub URL: https://github.com/barnent1/ciauth.git
 *
 * Copyright 2015 Barnhardt Enterprises, Inc.
 *
 * Licensed under GNU GPL v3.0 (See LICENSE) http://www.gnu.org/copyleft/gpl.html
 * 
 * Description: CodeIgniter Login Authorization Library. Created specifically
 * for PHP 5.5 and Codeigniter 3.0+
 * 
 * Requirements: PHP 5.5 or later and Codeigniter 3.0+
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/*
 * Run this script on your code igniter database to create the neccessary tables
 * for the ciauth authentication library.
 */

/* 
 * ciauth_user_accounts table 
 * This table holds the user accounts
 */

DROP TABLE IF EXISTS `ciauth_user_accounts`;
CREATE TABLE `ciauth_user_accounts` (
 `user_id` int(11) NOT NULL AUTO_INCREMENT,
 `email` varchar(50) NOT NULL,
 `username` varchar(40) NOT NULL,
 `password` varchar(255) NOT NULL,
 `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `last_login` timestamp NULL DEFAULT NULL,
 PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 * ciauth_user_profiles table 
 * This table holds the user profiles
 */

DROP TABLE IF EXISTS `ciauth_user_profiles`;
CREATE TABLE `ciauth_user_profiles` (
 `uprof_id` int(11) NOT NULL AUTO_INCREMENT,
 `profile_user_id_fk` int(11) NOT NULL,
 `first_name` varchar(40) NOT NULL,
 `last_name` varchar(40) NOT NULL,
 `address_1` varchar(60) NOT NULL,
 `address_2` varchar(60),
 `city` varchar(40) NOT NULL,
 `state` varchar(40) NOT NULL,
 `zip_code` varchar(10) NOT NULL,
 `company_name` varchar(40),
 `home_phone` varchar(15),
 `company_phone` varchar(15),
 `mobile_phone` varchar(15),
 `face_book_url` varchar(90),
 `twitter_url` varchar(90),
 `linkedin_url` varchar(90),
 `newsletter` char(1),
 PRIMARY KEY (`uprof_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 * ciauth_user_groups table 
 * This table holds the user groups
 */

DROP TABLE IF EXISTS `ciauth_user_groups`;
CREATE TABLE `ciauth_user_groups` (
 `group_id` int(11) NOT NULL AUTO_INCREMENT,
 `group_name` varchar(40) NOT NULL,
 `group_description` varchar(200) NOT NULL,
 PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 * ciauth_user_privileges table 
 * This table holds the user privileges
 */

DROP TABLE IF EXISTS `ciauth_user_privileges`;
CREATE TABLE `ciauth_user_privileges` (
 `privilege_id` int(11) NOT NULL AUTO_INCREMENT,
 `privilege_name` varchar(40) NOT NULL,
 `privilege_description` varchar(200) NOT NULL,
 PRIMARY KEY (`privilege_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 * ciauth_user_privileges_users table 
 * This table has records that tie the user to a privilege.
 */

DROP TABLE IF EXISTS `ciauth_user_privileges_users`;
CREATE TABLE `ciauth_user_privileges_users` (
 `upriv_id` int(11) NOT NULL AUTO_INCREMENT,
 `upriv_privilege_id_fk` int(11) NOT NULL,
 `upriv_user_id_fk` int(11) NOT NULL,
 PRIMARY KEY (`upriv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 * ciauth_user_privileges_groups table 
 * This table ties a user group to a privelege.
 */

DROP TABLE IF EXISTS `ciauth_user_privileges_groups`;
CREATE TABLE `ciauth_user_privileges_groups` (
 `upriv_id` int(11) NOT NULL AUTO_INCREMENT,
 `upriv_privilege_id_fk` int(11) NOT NULL,
 `upriv_group_id_fk` int(11) NOT NULL,
 PRIMARY KEY (`upriv_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/* 
 * ciauth_user_token table 
 * This table holds a user token used by the ciauth library.
 */

DROP TABLE IF EXISTS `ciauth_user_token`;
CREATE TABLE `ciauth_user_token` (
  `token` varchar(90) NOT NULL,
  `email` varchar(45) NOT NULL,
  `tstamp` int(11) NOT NULL,
  PRIMARY KEY (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/* 
 * ciauth_sessions table 
 * This table holds session information specific to ciauth.
 */

DROP TABLE IF EXISTS `ciauth_sessions`;
CREATE TABLE `ciauth_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data` longtext,
  `rnd_key` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

/* 
 * ciauth_navigation table 
 * This table holds site navigation elements.
 */

DROP TABLE IF EXISTS `ciauth_navigation`;
CREATE TABLE `ciauth_navigation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `anchor` varchar(60) NOT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `permissions` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
