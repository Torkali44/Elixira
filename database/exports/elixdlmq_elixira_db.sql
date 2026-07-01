-- Elixira MySQL export generated from SQLite
-- Generated: 2026-06-21 22:05:11
-- Target database: elixdlmq_elixira_db
-- Import via phpMyAdmin: Import tab -> choose this file -> Go

SET NAMES utf8mb4;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

CREATE DATABASE IF NOT EXISTS `elixdlmq_elixira_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `elixdlmq_elixira_db`;

DROP TABLE IF EXISTS `avatar_options`;
DROP TABLE IF EXISTS `blog_images`;
DROP TABLE IF EXISTS `blogs`;
DROP TABLE IF EXISTS `brands`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `contact_messages`;
DROP TABLE IF EXISTS `dxn_sponsor_codes`;
DROP TABLE IF EXISTS `dxn_team_requests`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `faqs`;
DROP TABLE IF EXISTS `home_page_sections`;
DROP TABLE IF EXISTS `item_country_prices`;
DROP TABLE IF EXISTS `item_images`;
DROP TABLE IF EXISTS `items`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `newsletter_subscribers`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `order_items`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `package_country_prices`;
DROP TABLE IF EXISTS `package_item`;
DROP TABLE IF EXISTS `packages`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `ratings`;
DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `special_item_offers`;
DROP TABLE IF EXISTS `special_requests`;
DROP TABLE IF EXISTS `taggables`;
DROP TABLE IF EXISTS `tags`;
DROP TABLE IF EXISTS `user_addresses`;
DROP TABLE IF EXISTS `user_points_transactions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `vendor_profiles`;

CREATE TABLE `avatar_options` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `image_url` TEXT NOT NULL,
  `link_url` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `gender` VARCHAR(255) NOT NULL DEFAULT 'both'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `blog_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `blog_id` BIGINT UNSIGNED NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `caption` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_blog_images_blog_id_blogs` FOREIGN KEY (`blog_id`) REFERENCES `blogs` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `blogs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `title_en` VARCHAR(255) NOT NULL,
  `title_ar` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `content_en` LONGTEXT NOT NULL,
  `content_ar` LONGTEXT NOT NULL,
  `summary_en` TEXT NULL,
  `summary_ar` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `published_at` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `video_url` VARCHAR(255) NULL,
UNIQUE KEY `blogs_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `brands` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `vendor_profile_id` BIGINT UNSIGNED NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `logo` VARCHAR(255) NULL,
  `description` LONGTEXT NULL,
  `instagram_link` VARCHAR(255) NULL,
  `tiktok_link` VARCHAR(255) NULL,
  `snapchat_link` VARCHAR(255) NULL,
  `twitter_link` VARCHAR(255) NULL,
  `store_link` VARCHAR(255) NULL,
  `store_link_description` TEXT NULL,
  `service_countries` TEXT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_brands_vendor_profile_id_vendor_profiles` FOREIGN KEY (`vendor_profile_id`) REFERENCES `vendor_profiles` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
UNIQUE KEY `brands_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache` (
  `key` VARCHAR(255) NOT NULL,
  `value` TEXT NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `cache_locks` (
  `key` VARCHAR(255) NOT NULL,
  `owner` VARCHAR(255) NOT NULL,
  `expiration` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `categories` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NULL,
  `image` VARCHAR(255) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `name_en` VARCHAR(255) NULL,
  `name_ar` VARCHAR(255) NULL,
  `description_en` LONGTEXT NULL,
  `description_ar` LONGTEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `contact_messages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `reason` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `read_at` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_contact_messages_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `dxn_sponsor_codes` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(255) NOT NULL,
  `sponsor_name` VARCHAR(255) NOT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
UNIQUE KEY `dxn_sponsor_codes_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `dxn_team_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `member_code` VARCHAR(255) NULL,
  `country` VARCHAR(255) NULL,
  `team_goal` TEXT NULL,
  `message` TEXT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `read_at` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `team_name` VARCHAR(255) NULL,
  `team_size` INT NULL,
  `team_members` TEXT NULL,
  `contract_accepted_at` DATETIME NULL,
  `sponsor_code` VARCHAR(255) NULL,
  `sponsor_name` VARCHAR(255) NULL,
  `gender` VARCHAR(255) NULL,
  `date_of_birth` DATE NULL,
  `id_number` VARCHAR(255) NULL,
  `passport_number` VARCHAR(255) NULL,
  `nationality` VARCHAR(255) NULL,
  `has_heir` TINYINT(1) NOT NULL DEFAULT 0,
  `heir_name` VARCHAR(255) NULL,
  `heir_relationship` VARCHAR(255) NULL,
  `heir_id_number` VARCHAR(255) NULL,
  `heir_passport_number` VARCHAR(255) NULL,
  `address` TEXT NULL,
  `address_country` VARCHAR(255) NULL,
  `address_city` VARCHAR(255) NULL,
  `postal_code` VARCHAR(255) NULL,
  `application_type` VARCHAR(255) NOT NULL DEFAULT 'new_distributor',
  `assigned_dxn_member_code` VARCHAR(255) NULL,
  `admin_notes` TEXT NULL,
  `dxn_tag_color` VARCHAR(255) NULL,
  `dxn_badge_image` VARCHAR(255) NULL,
  CONSTRAINT `fk_dxn_team_requests_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `failed_jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uuid` VARCHAR(255) NOT NULL,
  `connection` TEXT NOT NULL,
  `queue` TEXT NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `exception` LONGTEXT NOT NULL,
  `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `faqs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `question_en` VARCHAR(255) NOT NULL,
  `question_ar` VARCHAR(255) NOT NULL,
  `answer_en` TEXT NOT NULL,
  `answer_ar` TEXT NOT NULL,
  `is_published` TINYINT(1) NOT NULL DEFAULT 1,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `home_page_sections` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `slug` VARCHAR(255) NOT NULL,
  `admin_label` VARCHAR(255) NULL,
  `template` VARCHAR(255) NOT NULL DEFAULT 'paragraph',
  `title` TEXT NULL,
  `subtitle` TEXT NULL,
  `body` TEXT NULL,
  `image` VARCHAR(255) NULL,
  `button_label` VARCHAR(255) NULL,
  `button_url` VARCHAR(255) NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
UNIQUE KEY `home_page_sections_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `item_country_prices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item_id` BIGINT UNSIGNED NOT NULL,
  `country_code` VARCHAR(255) NOT NULL,
  `member_price` DECIMAL(12,2) NOT NULL,
  `guest_price` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_item_country_prices_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
UNIQUE KEY `item_country_prices_item_id_country_code_unique` (`item_id`, `country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `item_images` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item_id` BIGINT UNSIGNED NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_item_images_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` LONGTEXT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `stock` INT NOT NULL DEFAULT 0,
  `brand` VARCHAR(255) NULL,
  `points` INT NOT NULL DEFAULT 0,
  `brand_id` BIGINT UNSIGNED NULL,
  `rejection_reason` LONGTEXT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `reward_points` INT NOT NULL DEFAULT 0,
  `name_en` VARCHAR(255) NULL,
  `name_ar` VARCHAR(255) NULL,
  `description_en` LONGTEXT NULL,
  `description_ar` LONGTEXT NULL,
  `discount_percent` INT NULL,
  `long_description_en` LONGTEXT NULL,
  `long_description_ar` LONGTEXT NULL,
  CONSTRAINT `fk_items_category_id_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_items_brand_id_brands` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `job_batches` (
  `id` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `total_jobs` INT NOT NULL,
  `pending_jobs` INT NOT NULL,
  `failed_jobs` INT NOT NULL,
  `failed_job_ids` TEXT NOT NULL,
  `options` TEXT NULL,
  `cancelled_at` INT NULL,
  `created_at` INT NOT NULL,
  `finished_at` INT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `jobs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `queue` VARCHAR(255) NOT NULL,
  `payload` LONGTEXT NOT NULL,
  `attempts` INT NOT NULL,
  `reserved_at` INT NULL,
  `available_at` INT NOT NULL,
  `created_at` INT NOT NULL,
KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `migration` VARCHAR(255) NOT NULL,
  `batch` BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `newsletter_subscribers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
UNIQUE KEY `newsletter_subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `notifications` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `url` VARCHAR(255) NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `title_key` VARCHAR(255) NULL,
  `message_key` VARCHAR(255) NULL,
  `data` TEXT NULL,
  CONSTRAINT `fk_notifications_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_items` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `order_id` BIGINT UNSIGNED NOT NULL,
  `item_id` BIGINT UNSIGNED NULL,
  `quantity` INT NOT NULL,
  `price` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `package_id` BIGINT UNSIGNED NULL,
  `product_name` VARCHAR(255) NULL,
  CONSTRAINT `fk_order_items_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_order_items_order_id_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_order_items_package_id_packages` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(255) NOT NULL,
  `customer_phone` VARCHAR(255) NOT NULL,
  `total_amount` DECIMAL(12,2) NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `notes` LONGTEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `address` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `user_code` VARCHAR(255) NULL,
  CONSTRAINT `fk_orders_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `package_country_prices` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `package_id` BIGINT UNSIGNED NOT NULL,
  `country_code` VARCHAR(255) NOT NULL,
  `member_price` DECIMAL(12,2) NOT NULL,
  `guest_price` DECIMAL(12,2) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_package_country_prices_package_id_packages` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
UNIQUE KEY `package_country_prices_package_id_country_code_unique` (`package_id`, `country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `package_item` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `package_id` BIGINT UNSIGNED NOT NULL,
  `item_id` BIGINT UNSIGNED NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_package_item_package_id_packages` FOREIGN KEY (`package_id`) REFERENCES `packages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_package_item_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
UNIQUE KEY `package_item_package_id_item_id_unique` (`package_id`, `item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `packages` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `name_en` VARCHAR(255) NULL,
  `name_ar` VARCHAR(255) NULL,
  `description` LONGTEXT NULL,
  `description_en` LONGTEXT NULL,
  `description_ar` LONGTEXT NULL,
  `price` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `stock` INT NOT NULL DEFAULT 0,
  `reward_points` INT NOT NULL DEFAULT 0,
  `image` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `brand_id` BIGINT UNSIGNED NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'approved',
  `rejection_reason` LONGTEXT NULL,
  `long_description_en` LONGTEXT NULL,
  `long_description_ar` LONGTEXT NULL,
  CONSTRAINT `fk_packages_brand_id_brands` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_reset_tokens` (
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `ratings` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `rateable_type` VARCHAR(255) NOT NULL,
  `rateable_id` BIGINT UNSIGNED NOT NULL,
  `rating` INT NOT NULL,
  `comment` TEXT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `image` VARCHAR(255) NULL,
  CONSTRAINT `fk_ratings_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
KEY `ratings_rateable_type_rateable_id_index` (`rateable_type`, `rateable_id`),
UNIQUE KEY `ratings_user_id_rateable_id_rateable_type_unique` (`user_id`, `rateable_id`, `rateable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reservations` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(255) NOT NULL,
  `reservation_date` DATE NOT NULL,
  `reservation_time` VARCHAR(255) NOT NULL,
  `guests` INT NOT NULL,
  `notes` LONGTEXT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `reviews` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(255) NOT NULL DEFAULT 'direct',
  `avatar` VARCHAR(255) NULL,
  `name` VARCHAR(255) NULL,
  `age` VARCHAR(255) NULL,
  `skin_type` VARCHAR(255) NULL,
  `rating` INT NULL,
  `content` LONGTEXT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `sessions` (
  `id` VARCHAR(255) NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `ip_address` VARCHAR(255) NULL,
  `user_agent` TEXT NULL,
  `payload` LONGTEXT NOT NULL,
  `last_activity` INT NOT NULL,
KEY `sessions_user_id_index` (`user_id`),
KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `special_item_offers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item_id` BIGINT UNSIGNED NOT NULL,
  `special_request_id` BIGINT UNSIGNED NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `target_phone` VARCHAR(255) NULL,
  `target_email` VARCHAR(255) NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `used_quantity` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_special_item_offers_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_special_item_offers_special_request_id_special_requests` FOREIGN KEY (`special_request_id`) REFERENCES `special_requests` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_special_item_offers_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `special_requests` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item_id` BIGINT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NULL,
  `name` VARCHAR(255) NULL,
  `phone` VARCHAR(255) NOT NULL,
  `status` VARCHAR(255) NOT NULL DEFAULT 'pending',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `email` VARCHAR(255) NULL,
  CONSTRAINT `fk_special_requests_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_special_requests_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `taggables` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `tag_id` BIGINT UNSIGNED NOT NULL,
  `taggable_type` VARCHAR(255) NOT NULL,
  `taggable_id` BIGINT UNSIGNED NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_taggables_tag_id_tags` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
KEY `taggables_taggable_type_taggable_id_index` (`taggable_type`, `taggable_id`),
UNIQUE KEY `taggables_tag_id_taggable_id_taggable_type_unique` (`tag_id`, `taggable_id`, `taggable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `tags` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_addresses` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `address` TEXT NOT NULL,
  `is_main` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_user_addresses_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user_points_transactions` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `order_id` BIGINT UNSIGNED NULL,
  `item_id` BIGINT UNSIGNED NULL,
  `points` INT NOT NULL,
  `description_en` VARCHAR(255) NOT NULL,
  `description_ar` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  CONSTRAINT `fk_user_points_transactions_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_points_transactions_order_id_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_user_points_transactions_item_id_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_verified_at` DATETIME NULL,
  `password` VARCHAR(255) NOT NULL,
  `remember_token` VARCHAR(255) NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `role` VARCHAR(255) NOT NULL DEFAULT 'user',
  `is_suspended` TINYINT(1) NOT NULL DEFAULT 0,
  `phone` VARCHAR(255) NULL,
  `user_code` VARCHAR(255) NULL,
  `avatar` VARCHAR(255) NULL,
  `avatar_option_id` BIGINT UNSIGNED NULL,
  `gender` VARCHAR(255) NULL,
  `theme` VARCHAR(255) NOT NULL DEFAULT 'dark',
  `locale` VARCHAR(255) NOT NULL DEFAULT 'en',
  `total_points` INT NOT NULL DEFAULT 0,
  `is_dxn_verified` TINYINT(1) NOT NULL DEFAULT 0,
  `dxn_member_code` VARCHAR(255) NULL,
  `dxn_tag_color` VARCHAR(255) NULL,
  `dxn_badge_image` VARCHAR(255) NULL,
  `dxn_verified_at` DATETIME NULL,
  `cart_data` LONGTEXT NULL,
  `email_verification_code` VARCHAR(255) NULL,
  `email_verification_code_expires_at` DATETIME NULL,
  CONSTRAINT `fk_users_avatar_option_id_avatar_options` FOREIGN KEY (`avatar_option_id`) REFERENCES `avatar_options` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
UNIQUE KEY `users_email_unique` (`email`),
UNIQUE KEY `users_user_code_unique` (`user_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `vendor_profiles` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `brand_name` VARCHAR(255) NULL,
  `brand_logo` VARCHAR(255) NULL,
  `brand_description` TEXT NULL,
  `instagram_link` VARCHAR(255) NULL,
  `tiktok_link` VARCHAR(255) NULL,
  `snapchat_link` VARCHAR(255) NULL,
  `other_links` TEXT NULL,
  `store_link` VARCHAR(255) NULL,
  `store_link_description` TEXT NULL,
  `service_countries` TEXT NULL,
  `product_types` TEXT NULL,
  `payment_method` VARCHAR(255) NOT NULL DEFAULT 'cash_on_delivery',
  `status` VARCHAR(255) NOT NULL DEFAULT 'draft',
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  `verification_document` VARCHAR(255) NULL,
  `rejection_reason` LONGTEXT NULL,
  `commercial_registration_number` VARCHAR(255) NULL,
  `onboarding_step` INT NOT NULL DEFAULT 1,
  `subscription_payment_receipt` VARCHAR(255) NULL,
  `subscription_payment_status` VARCHAR(255) NOT NULL DEFAULT 'not_required',
  `subscription_plan` VARCHAR(255) NULL,
  `subscription_starts_at` DATETIME NULL,
  `subscription_ends_at` DATETIME NULL,
  CONSTRAINT `fk_vendor_profiles_user_id_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Data for table `avatar_options`
INSERT INTO `avatar_options` (`id`, `name`, `image_url`, `link_url`, `is_active`, `sort_order`, `created_at`, `updated_at`, `gender`) VALUES
(1, 'Avatar 1', 'https://framerusercontent.com/images/cTc7CUtNbTmlTgoiKuHSwOHME.png', 'https://framerusercontent.com/images/cTc7CUtNbTmlTgoiKuHSwOHME.png', 1, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(2, 'Avatar 2', 'https://framerusercontent.com/images/xujOvWlIH4jCpEHwRSO8fL3AZyM.png', 'https://framerusercontent.com/images/xujOvWlIH4jCpEHwRSO8fL3AZyM.png', 1, 2, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(3, 'Avatar 3', 'https://framerusercontent.com/images/voEeLI8QvLxIBheChMgZpIZDBDw.png', 'https://framerusercontent.com/images/voEeLI8QvLxIBheChMgZpIZDBDw.png', 1, 3, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(4, 'Avatar 4', 'https://framerusercontent.com/images/P6B3UqKPpI7pUX8hpOGEuB7DoYI.png', 'https://framerusercontent.com/images/P6B3UqKPpI7pUX8hpOGEuB7DoYI.png', 1, 4, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(5, 'Avatar 5', 'https://framerusercontent.com/images/ZOmjcnCegPgIJe774bHLeiqGoRY.png', 'https://framerusercontent.com/images/ZOmjcnCegPgIJe774bHLeiqGoRY.png', 1, 5, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(6, 'Avatar 6', 'https://framerusercontent.com/images/aH4TSB4QigZBUovRTOzJbNfmE8.png', 'https://framerusercontent.com/images/aH4TSB4QigZBUovRTOzJbNfmE8.png', 1, 6, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(7, 'Avatar 7', 'https://framerusercontent.com/images/vjNbwG6wtp9Zat3QDbEDG5SQ8nc.png', 'https://framerusercontent.com/images/vjNbwG6wtp9Zat3QDbEDG5SQ8nc.png', 1, 7, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(8, 'Avatar 8', 'https://framerusercontent.com/images/yLvdWmXt1qfpzFexvRPL1YPjEM.png', 'https://framerusercontent.com/images/yLvdWmXt1qfpzFexvRPL1YPjEM.png', 1, 8, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(9, 'Avatar 9', 'https://framerusercontent.com/images/L0MgVueQuuaTbIG2RDygjv6nxw.png', 'https://framerusercontent.com/images/L0MgVueQuuaTbIG2RDygjv6nxw.png', 1, 9, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(10, 'Avatar 10', 'https://framerusercontent.com/images/cyZY6rN0VQ2rTCXAp8vDkwwfs.png', 'https://framerusercontent.com/images/cyZY6rN0VQ2rTCXAp8vDkwwfs.png', 1, 10, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(11, 'Avatar 11', 'https://framerusercontent.com/images/avsgw3MlrBZ7Qemx2LDUzfksapA.png', 'https://framerusercontent.com/images/avsgw3MlrBZ7Qemx2LDUzfksapA.png', 1, 11, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(12, 'Avatar 12', 'https://framerusercontent.com/images/tNsNvr6rtFzILJLih7KTMe4uM.png', 'https://framerusercontent.com/images/tNsNvr6rtFzILJLih7KTMe4uM.png', 1, 12, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(13, 'Avatar 13', 'https://framerusercontent.com/images/QHfWARUm32FA9v9bgBIZeTDFaB8.png', 'https://framerusercontent.com/images/QHfWARUm32FA9v9bgBIZeTDFaB8.png', 1, 13, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(14, 'Avatar 14', 'https://framerusercontent.com/images/epaiBXj1vYRcJ7bEafNzHniJ8gQ.png', 'https://framerusercontent.com/images/epaiBXj1vYRcJ7bEafNzHniJ8gQ.png', 1, 14, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(15, 'Avatar 15', 'https://framerusercontent.com/images/yMGFyp1B3WEQPWVHuC2AOcqCwBk.png', 'https://framerusercontent.com/images/yMGFyp1B3WEQPWVHuC2AOcqCwBk.png', 1, 15, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(16, 'Avatar 16', 'https://framerusercontent.com/images/AW5gsxnLBvhE7bhUymsSWpcAP0.png', 'https://framerusercontent.com/images/AW5gsxnLBvhE7bhUymsSWpcAP0.png', 1, 16, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(17, 'Avatar 17', 'https://framerusercontent.com/images/0pOGEkOl3QOA0AOhql07dLjouU.png', 'https://framerusercontent.com/images/0pOGEkOl3QOA0AOhql07dLjouU.png', 1, 17, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(18, 'Avatar 18', 'https://framerusercontent.com/images/Xdax07q3fD8YGG6qtDgZOZEaqI.png', 'https://framerusercontent.com/images/Xdax07q3fD8YGG6qtDgZOZEaqI.png', 1, 18, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(19, 'Avatar 19', 'https://framerusercontent.com/images/rMJN1hMOPP8cSGd8LdmmlMesy8.png', 'https://framerusercontent.com/images/rMJN1hMOPP8cSGd8LdmmlMesy8.png', 1, 19, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(20, 'Avatar 20', 'https://framerusercontent.com/images/iyDK7k3FedurGjdTkG1KSJYm8no.png', 'https://framerusercontent.com/images/iyDK7k3FedurGjdTkG1KSJYm8no.png', 1, 20, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(21, 'Avatar 21', 'https://framerusercontent.com/images/Ryq4xjuMhGxgQzm7NiX6xlq3938.png', 'https://framerusercontent.com/images/Ryq4xjuMhGxgQzm7NiX6xlq3938.png', 1, 21, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(22, 'Avatar 22', 'https://framerusercontent.com/images/YCyyVb7j8C5U4vDHPAPqKNHIfAc.png', 'https://framerusercontent.com/images/YCyyVb7j8C5U4vDHPAPqKNHIfAc.png', 1, 22, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(23, 'Avatar 23', 'https://framerusercontent.com/images/CD114mrqzRMe6TQ3ieg8ZQxUk.png', 'https://framerusercontent.com/images/CD114mrqzRMe6TQ3ieg8ZQxUk.png', 1, 23, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both'),
(24, 'Avatar 24', 'https://framerusercontent.com/images/7wRDToSNM4pfN5ZhkkMQRo4zuY.png', 'https://framerusercontent.com/images/7wRDToSNM4pfN5ZhkkMQRo4zuY.png', 1, 24, '2026-06-20 18:03:34', '2026-06-20 18:03:34', 'both');

-- Data for table `categories`
INSERT INTO `categories` (`id`, `name`, `description`, `image`, `created_at`, `updated_at`, `name_en`, `name_ar`, `description_en`, `description_ar`) VALUES
(1, 'Cleansers', 'Face washes, balms, and micellar waters for a fresh canvas.', NULL, '2026-06-20 18:03:34', '2026-06-20 18:03:34', NULL, NULL, NULL, NULL),
(2, 'Moisturizers', 'Creams, gels, and lotions to lock in hydration.', NULL, '2026-06-20 18:03:34', '2026-06-20 18:03:34', NULL, NULL, NULL, NULL),
(3, 'Serums & Treatments', 'Targeted actives for brightening, renewal, and balance.', NULL, '2026-06-20 18:03:34', '2026-06-20 18:03:34', NULL, NULL, NULL, NULL),
(4, 'Sun care', 'SPF and daily protection for healthy-looking skin.', NULL, '2026-06-20 18:03:34', '2026-06-20 18:03:34', NULL, NULL, NULL, NULL),
(5, 'Masks & exfoliants', 'Weekly resets—clay, enzyme, and gentle scrubs.', NULL, '2026-06-20 18:03:34', '2026-06-20 18:03:34', NULL, NULL, NULL, NULL),
(6, 'Body care', 'Lotions, oils, and essentials from neck to toe.', NULL, '2026-06-20 18:03:34', '2026-06-20 18:03:34', NULL, NULL, NULL, NULL);

-- Data for table `dxn_sponsor_codes`
INSERT INTO `dxn_sponsor_codes` (`id`, `code`, `sponsor_name`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'DXN200', 'Elixira Admin', 1, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(2, 'DXN100', 'Primary Sponsor', 1, 2, '2026-06-20 18:03:34', '2026-06-20 18:03:34');

-- Data for table `faqs`
INSERT INTO `faqs` (`id`, `question_en`, `question_ar`, `answer_en`, `answer_ar`, `is_published`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'What is Elixira?', 'ما هي إكسيرا؟', 'Elixira is a curated wellness marketplace connecting you with premium skincare, superfoods, and beauty brands from verified vendors across the region.', 'إكسيرا هي منصة رفاهية متكاملة تربطك بأفضل منتجات العناية بالبشرة والمكملات الغذائية الطبيعية ومستحضرات الجمال من بائعين موثوقين في المنطقة.', 1, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(2, 'How do I track my order?', 'كيف أتتبع طلبيتي؟', 'You can track your order using the "Track Order" page in the navigation menu. Enter your order number and email address to see real-time status updates.', 'يمكنك تتبع طلبيتك من خلال صفحة "تتبع الطلب" في قائمة التصفح. أدخل رقم الطلب وبريدك الإلكتروني للاطلاع على آخر تحديثات الشحن.', 1, 2, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(3, 'What payment methods do you accept?', 'ما طرق الدفع المتاحة؟', 'We accept all major credit and debit cards (Visa, Mastercard), as well as Apple Pay. All transactions are secured with industry-standard encryption.', 'نقبل جميع بطاقات الائتمان والخصم الرئيسية (فيزا، ماستركارد) بالإضافة إلى Apple Pay. جميع المعاملات مؤمّنة بأحدث معايير التشفير.', 1, 3, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(4, 'Can I return a product?', 'هل يمكنني إرجاع منتج؟', 'Yes. We offer a hassle-free return window of 14 days from the date of delivery, provided the product is unused and in its original packaging. Contact our support team to initiate a return.', 'نعم. نتيح لك نافذة إرجاع مريحة مدتها 14 يومًا من تاريخ التسليم، شريطة أن يكون المنتج غير مستخدم وفي عبوته الأصلية. تواصل مع فريق الدعم لبدء طلب الإرجاع.', 1, 4, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(5, 'How do I become a vendor on Elixira?', 'كيف أصبح بائعًا على إكسيرا؟', 'Register for a vendor account and complete your profile with your brand details and verification documents. Our team reviews applications within 2–3 business days.', 'سجّل حسابًا كبائع وأكمل ملفك الشخصي بتفاصيل علامتك التجارية ووثائق التحقق. يراجع فريقنا الطلبات خلال 2 إلى 3 أيام عمل.', 1, 5, '2026-06-20 18:03:34', '2026-06-20 18:03:34');

-- Data for table `home_page_sections`
INSERT INTO `home_page_sections` (`id`, `slug`, `admin_label`, `template`, `title`, `subtitle`, `body`, `image`, `button_label`, `button_url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'hero', 'Hero (top banner)', 'hero', 'Welcome to Elixira', 'Clean, potent skincare rooted in nature — curated for your daily ritual.', '{"secondary_button_label":"Go Cart","secondary_button_url":"\\/cart"}', NULL, 'Enter Store', '/menu', 10, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(2, 'loved_by', 'Featured products heading', 'heading', 'Loved by many', 'Hand-picked formulas your admin marks as featured appear here.', NULL, NULL, NULL, NULL, 20, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(3, 'featured_grid', 'Featured product cards', 'featured_products', NULL, NULL, NULL, NULL, 'View all products', '/menu', 30, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(4, 'story', 'Brand story (text + image)', 'split', 'Our philosophy', NULL, 'At Elixira, we believe that true beauty and wellbeing stem from nature. Every formula is developed with clear ingredient lists and honest claims — so you always know what touches your skin.\n\nBuild a morning and night ritual from one trusted catalogue, and track every order from checkout to delivery.', NULL, 'Explore the shop', '/explore', 40, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(5, 'newsletter', 'Newsletter strip', 'newsletter', 'Unlock exclusive launches', 'Curated tips and members-only offers. No spam — good stuff only.', NULL, NULL, 'Subscribe', '#', 50, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34'),
(6, 'values', 'Three value cards (JSON in body)', 'icon_cards', 'Why Elixira', NULL, '[{"icon":"fa-leaf","title":"Natural ingredients","text":"Crafted from pure ingredients that respect your skin."},{"icon":"fa-flask","title":"Potent formulas","text":"Science-backed actives for visible results."},{"icon":"fa-hand-sparkles","title":"Daily ritual","text":"Turn your routine into a moment of care."}]', NULL, NULL, NULL, 45, 1, '2026-06-20 18:03:34', '2026-06-20 18:03:34');

-- Data for table `migrations`
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_06_173508_create_categories_table', 1),
(5, '2026_01_06_173508_create_items_table', 1),
(6, '2026_01_06_173508_create_orders_table', 1),
(7, '2026_01_06_173509_create_order_items_table', 1),
(8, '2026_01_06_173510_create_reservations_table', 1),
(9, '2026_01_06_190516_add_role_to_users_table', 1),
(10, '2026_01_06_203844_add_address_to_orders_table', 1),
(11, '2026_04_16_000001_create_home_page_sections_table', 1),
(12, '2026_04_16_030351_add_stock_to_items_table', 1),
(13, '2026_04_16_141736_create_item_images_table', 1),
(14, '2026_04_16_212500_add_brand_and_points_to_items_table', 1),
(15, '2026_04_16_224913_add_is_suspended_to_users_table', 1),
(16, '2026_04_16_233850_create_reviews_table', 1),
(17, '2026_04_21_220043_add_phone_and_user_code_to_users_table', 1),
(18, '2026_04_21_220044_add_user_id_to_orders_table', 1),
(19, '2026_04_23_140000_add_avatar_to_users_table', 1),
(20, '2026_04_23_150000_create_avatar_options_table', 1),
(21, '2026_04_23_150100_add_avatar_option_id_to_users_table', 1),
(22, '2026_04_28_223939_add_gender_and_image_upload_to_avatar_options_table', 1),
(23, '2026_04_28_223950_add_gender_to_users_table', 1),
(24, '2026_05_01_000731_create_special_requests_table', 1),
(25, '2026_05_02_005000_add_email_and_private_offers', 1),
(26, '2026_05_03_122751_create_user_addresses_table', 1),
(27, '2026_05_09_232047_create_vendor_profiles_table', 1),
(28, '2026_05_10_010636_add_verification_document_to_vendor_profiles_table', 1),
(29, '2026_05_12_164352_create_brands_table', 1),
(30, '2026_05_13_223843_create_ratings_table', 1),
(31, '2026_05_15_234930_add_rejection_reason_to_vendor_profiles_table', 1),
(32, '2026_05_16_001022_alter_status_enum_in_vendor_profiles_table', 1),
(33, '2026_05_16_212248_add_status_and_rejection_reason_to_items_table', 1),
(34, '2026_05_16_231816_alter_status_enum_in_items_table', 1),
(35, '2026_06_02_150000_create_notifications_table', 1),
(36, '2026_06_05_191000_create_newsletter_subscribers_table', 1),
(37, '2026_06_08_123536_create_faqs_table', 1),
(38, '2026_06_08_123552_create_blogs_table', 1),
(39, '2026_06_08_165217_add_translation_keys_to_notifications_table', 1),
(40, '2026_06_08_add_theme_and_locale_to_users_table', 1),
(41, '2026_06_13_021227_add_reward_points_to_items_and_users_table', 1),
(42, '2026_06_13_021229_add_bilingual_fields_to_items_and_categories_table', 1),
(43, '2026_06_13_021230_add_video_url_to_blogs_and_create_blog_images_table', 1),
(44, '2026_06_13_113624_create_user_points_transactions_table', 1),
(45, '2026_06_14_210056_create_item_country_prices_table', 1),
(46, '2026_06_14_210057_add_vendor_subscription_fields_to_vendor_profiles_table', 1),
(47, '2026_06_14_210058_create_contact_messages_table', 1),
(48, '2026_06_14_210059_create_dxn_team_requests_table', 1),
(49, '2026_06_14_213947_add_team_fields_to_dxn_team_requests_table', 1),
(50, '2026_06_14_215718_create_dxn_sponsor_codes_table', 1),
(51, '2026_06_14_215719_add_distributor_fields_to_dxn_team_requests_table', 1),
(52, '2026_06_16_081423_add_dxn_member_fields_to_users_table', 1),
(53, '2026_06_16_081424_add_application_type_to_dxn_team_requests_table', 1),
(54, '2026_06_16_081425_add_subscription_plan_timers_to_vendor_profiles_table', 1),
(55, '2026_06_17_231934_create_tags_table', 1),
(56, '2026_06_18_181853_create_packages_table', 1),
(57, '2026_06_18_181854_create_package_country_prices_table', 1),
(58, '2026_06_18_182000_add_package_support_to_order_items_table', 1),
(59, '2026_06_18_212520_add_discount_percent_to_items_table', 1),
(60, '2026_06_18_212521_add_brand_id_to_packages_table', 1),
(61, '2026_06_18_212522_add_dxn_display_fields_to_dxn_team_requests_table', 1),
(62, '2026_06_18_212522_add_image_to_ratings_table', 1),
(63, '2026_06_19_122128_add_item_id_to_user_points_transactions_table_if_missing', 1),
(64, '2026_06_19_124014_add_cart_data_to_users_table', 1),
(65, '2026_06_19_141322_add_email_verification_otp_to_users_table', 1),
(66, '2026_06_19_181237_add_status_to_packages_table', 1),
(67, '2026_06_19_183352_add_bilingual_long_description_to_items_and_packages', 1);

-- Data for table `users`
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`, `is_suspended`, `phone`, `user_code`, `avatar`, `avatar_option_id`, `gender`, `theme`, `locale`, `total_points`, `is_dxn_verified`, `dxn_member_code`, `dxn_tag_color`, `dxn_badge_image`, `dxn_verified_at`, `cart_data`, `email_verification_code`, `email_verification_code_expires_at`) VALUES
(1, 'Elixira Admin', 'eshraqa.melody00@gmail.com', '2026-06-21 19:50:17', '$2y$12$H6IsECgcaqaQ0JQ8PNvi2.AdMFBB5nOIM8eTJITXDoCVhogBIUWfC', NULL, '2026-06-20 18:03:34', '2026-06-21 19:50:17', 'admin', 0, NULL, NULL, NULL, NULL, NULL, 'light', 'en', 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
-- Import complete.