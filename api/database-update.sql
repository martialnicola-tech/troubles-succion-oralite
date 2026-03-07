-- Mise à jour de la base de données - À exécuter dans phpMyAdmin
-- Ce fichier est sûr à ré-exécuter (IF NOT EXISTS / IF NOT EXISTS protège des doublons)

-- ─── Table sessions temporaires d'inscription ───
CREATE TABLE IF NOT EXISTS `registration_sessions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `session_token` VARCHAR(255) NOT NULL UNIQUE,
  `email` VARCHAR(255) NOT NULL,
  `form_data` TEXT NOT NULL,
  `stripe_checkout_session_id` VARCHAR(255) DEFAULT NULL,
  `expires_at` DATETIME NOT NULL,
  `completed` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_token` (`session_token`),
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Table paiements ───
CREATE TABLE IF NOT EXISTS `payments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `professionnel_id` INT(11) NOT NULL,
  `stripe_payment_id` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `currency` VARCHAR(3) NOT NULL DEFAULT 'CHF',
  `status` VARCHAR(50) NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT NULL,
  `payment_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `metadata` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`professionnel_id`) REFERENCES `professionnels`(`id`) ON DELETE CASCADE,
  INDEX `idx_professionnel` (`professionnel_id`),
  INDEX `idx_stripe_payment` (`stripe_payment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Table webhooks Stripe ───
CREATE TABLE IF NOT EXISTS `stripe_webhooks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `event_id` VARCHAR(255) NOT NULL UNIQUE,
  `event_type` VARCHAR(100) NOT NULL,
  `payload` TEXT NOT NULL,
  `processed` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_event_type` (`event_type`),
  INDEX `idx_processed` (`processed`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Colonnes supplémentaires sur professionnels ───
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS abonnement_actif TINYINT(1) DEFAULT 1;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS date_fin_abonnement DATE DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS canton VARCHAR(50) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS ville VARCHAR(100) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS npa VARCHAR(10) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif';
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS stripe_customer_id VARCHAR(255) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS stripe_subscription_id VARCHAR(255) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS subscription_start_date DATETIME DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS subscription_end_date DATETIME DEFAULT NULL;
