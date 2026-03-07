-- Base de données pour le système d'inscription professionnelle
-- À exécuter dans phpMyAdmin sur Hostinger

CREATE TABLE IF NOT EXISTS `professionnels` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `specialite` VARCHAR(100) NOT NULL,
  `prenom` VARCHAR(100) NOT NULL,
  `nom` VARCHAR(100) NOT NULL,
  `adresse` VARCHAR(255) NOT NULL,
  `code_postal` VARCHAR(10) NOT NULL,
  `lieu` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `telephone` VARCHAR(50) DEFAULT NULL,
  `site_web` VARCHAR(255) DEFAULT NULL,
  `backlink_verified` TINYINT(1) DEFAULT 0,
  `backlink_verified_date` DATETIME DEFAULT NULL,
  `status` ENUM('pending', 'active', 'expired', 'cancelled') DEFAULT 'pending',
  `stripe_customer_id` VARCHAR(255) DEFAULT NULL,
  `stripe_subscription_id` VARCHAR(255) DEFAULT NULL,
  `subscription_start_date` DATETIME DEFAULT NULL,
  `subscription_end_date` DATETIME DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_status` (`status`),
  INDEX `idx_specialite` (`specialite`),
  INDEX `idx_lieu` (`lieu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les logs de vérification de backlink
CREATE TABLE IF NOT EXISTS `backlink_checks` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `professionnel_id` INT(11) NOT NULL,
  `site_web` VARCHAR(255) NOT NULL,
  `check_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `found` TINYINT(1) NOT NULL,
  `details` TEXT DEFAULT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`professionnel_id`) REFERENCES `professionnels`(`id`) ON DELETE CASCADE,
  INDEX `idx_professionnel` (`professionnel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table pour les paiements
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

-- Table pour les webhooks Stripe
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

-- Table pour les sessions temporaires d'inscription
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

-- Table pour l'administration
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Créer un utilisateur admin par défaut (mot de passe: changeme123)
-- IMPORTANT: Changez ce mot de passe après la première connexion!
INSERT INTO `admin_users` (`username`, `password_hash`, `email`) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@troubles-succion-oralite.ch')
ON DUPLICATE KEY UPDATE username = username;

-- Mise à jour pour l'annuaire
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS abonnement_actif TINYINT(1) DEFAULT 1;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS date_fin_abonnement DATE DEFAULT NULL;

-- Colonnes pour la recherche géographique
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS canton VARCHAR(50) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS ville VARCHAR(100) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS npa VARCHAR(10) DEFAULT NULL;
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS statut ENUM('actif', 'inactif', 'suspendu') DEFAULT 'actif';

-- Index pour améliorer les performances de recherche
CREATE INDEX IF NOT EXISTS idx_canton ON professionnels(canton);
CREATE INDEX IF NOT EXISTS idx_specialite ON professionnels(specialite);
CREATE INDEX IF NOT EXISTS idx_ville ON professionnels(ville);
CREATE INDEX IF NOT EXISTS idx_npa ON professionnels(npa);
CREATE INDEX IF NOT EXISTS idx_statut_actif ON professionnels(statut, abonnement_actif);
