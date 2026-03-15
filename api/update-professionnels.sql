-- ============================================================
-- MISE À JOUR DES PROFESSIONNELS — mars 2026
-- Ajout téléphones, adresses, noms de cabinet
-- Correction données manquantes
-- ============================================================

-- Ajouter la colonne cabinet si elle n'existe pas encore
ALTER TABLE professionnels ADD COLUMN IF NOT EXISTS cabinet VARCHAR(255) DEFAULT NULL;

-- ============================================================
-- OSTÉOPATHES
-- ============================================================

UPDATE professionnels SET telephone='077 440 56 80' WHERE prenom='Céline' AND nom='Rossier';

UPDATE professionnels SET telephone='079 451 37 38', cabinet='Espace de santé intégrative', adresse='Route du stand 11' WHERE prenom='Charline' AND nom='Sanchez';

UPDATE professionnels SET telephone='077 457 88 18', cabinet='Espace de santé intégrative', adresse='Route du stand 11' WHERE prenom='Adeline' AND nom='Emery';

UPDATE professionnels SET cabinet='Centre de Prévention et Santé de Grône' WHERE prenom='Mylène' AND nom='Vogel-Gillioz';

UPDATE professionnels SET adresse='Ancienne route cantonale 40' WHERE prenom='Lauriane' AND nom='Gay';

UPDATE professionnels SET telephone='079 191 99 26', adresse='Chemin des Truits 31' WHERE prenom='Stéphanie' AND nom='Borboën-Besse';

UPDATE professionnels SET telephone='076 586 50 54', adresse='Place de l\'Hôtel de Ville 2' WHERE prenom='Véronique' AND nom='Mahaux';

-- ============================================================
-- SAGES-FEMMES
-- ============================================================

UPDATE professionnels SET telephone='079 458 39 54', cabinet='Maison de naissance de Lucines', lieu='Collombey-Muraz', code_postal='1868', npa='1868', canton='VS' WHERE prenom='Esther' AND nom='Lüdemann';

UPDATE professionnels SET telephone='079 243 28 48', adresse='Esplanade de l\'Eglise 2' WHERE prenom='Claire' AND nom='Pasquereau';

UPDATE professionnels SET telephone='076 541 42 23', cabinet='Maison de Naissance des Lucines', adresse='Chemin du Verger 3b' WHERE prenom='Fabienne' AND nom='Clerc';

UPDATE professionnels SET telephone='078 607 54 59', cabinet='Cabinet Graine2vie', adresse='Route de Champagneule 29' WHERE prenom='Carolyn' AND nom='Giroud Dorsaz';

UPDATE professionnels SET telephone='079 740 70 10' WHERE prenom='Stéphanie' AND nom='Anderegg';

UPDATE professionnels SET telephone='076 489 35 68', cabinet='Cabinet des Sages-Femmes du Chablais', adresse='Chemin du Verger 3' WHERE prenom='Juliette' AND nom='Cloche';

UPDATE professionnels SET telephone='076 326 39 88', adresse='Route de la Rasse 7', email='delphineblot-sagefemme@protonmail.com' WHERE prenom='Delphine' AND nom='Blot-Hanssens';

UPDATE professionnels SET telephone='079 701 17 64', cabinet='Maison de Naissance des Lucines', adresse='Chemin du Verger 3b' WHERE prenom='Martine' AND nom='Erard';

UPDATE professionnels SET telephone='079 623 05 62' WHERE prenom='Christine' AND nom='Joost';

UPDATE professionnels SET telephone='078 303 14 16' WHERE prenom='Laure' AND nom='Lodovici';

-- Noémie Perrion : pas d'adresse précise, juste région
UPDATE professionnels SET adresse=NULL, lieu='Martigny (région)', npa=NULL, code_postal=NULL WHERE prenom='Noémie' AND nom='Perrion';

UPDATE professionnels SET telephone='078 603 97 52', adresse='Route du Village 73' WHERE prenom='Alexandra' AND nom='Veuthey';

UPDATE professionnels SET telephone='079 739 88 08', lieu='Chablais VS-VD' WHERE prenom='Barbara' AND nom='Peretten';

UPDATE professionnels SET telephone='076 418 38 75', lieu='Sion et environs' WHERE prenom='Clémentine' AND nom='Fasel';

UPDATE professionnels SET telephone='079 321 16 54' WHERE prenom='Martine' AND nom='Gollut Bollschweiler';

-- Martine Gollut Bollschweiler — nouvelle sage-femme
INSERT IGNORE INTO professionnels (specialite, prenom, nom, adresse, code_postal, lieu, email, telephone, site_web, backlink_verified, status, abonnement_actif, subscription_start_date, subscription_end_date, canton, npa) VALUES
('sage-femme', 'Martine', 'Gollut Bollschweiler', NULL, '1867', 'Ollon', 'noemail.gollut@troubles-succion-oralite.ch', '079 321 16 54', NULL, 0, 'pending', 0, NULL, NULL, 'VD', '1867');

-- ============================================================
-- IBCLC / CONSULTANTE EN LACTATION
-- ============================================================

UPDATE professionnels SET
  telephone='078 260 07 79',
  cabinet='Maison de la Santé de Vétroz',
  site_web='https://aventurelacteee.ch',
  specialite='ibclc, consultante-lactation'
WHERE prenom='Véronique' AND nom='Taton';

UPDATE professionnels SET
  telephone='079 249 23 81',
  cabinet='Espace de santé intégrative',
  adresse='Route du stand 11',
  site_web='https://www.carolegenoud.ch',
  specialite='ibclc, consultante-lactation',
  status='active',
  abonnement_actif=1,
  subscription_start_date=NOW(),
  subscription_end_date='2026-12-31'
WHERE prenom='Carole' AND nom='Genoud';

-- ============================================================
-- LOGOPÉDISTE
-- ============================================================

UPDATE professionnels SET telephone='079 524 10 76', adresse='Route du Stand 11' WHERE prenom='Smaranda' AND nom='Veuillet';

-- ============================================================
-- ORL / DENTISTES
-- ============================================================

UPDATE professionnels SET telephone='032 731 65 30', adresse='Grand\'rue 38' WHERE prenom='Mirko' AND nom='Bozin';

-- Kodzo Konu — HFR Fribourg (hôpital, pas d'adresse de cabinet)
UPDATE professionnels SET telephone='026 306 36 10', cabinet='HFR Fribourg', adresse=NULL, lieu='Fribourg', code_postal='1708', npa='1708' WHERE prenom='Kodzo' AND nom='Konu';

-- Victor Colin — HFR Fribourg
UPDATE professionnels SET telephone='026 306 36 10', cabinet='HFR Fribourg', adresse=NULL, lieu='Fribourg', code_postal='1708', npa='1708' WHERE prenom='Victor' AND nom='Colin';

-- ============================================================
-- PHYSIOTHÉRAPEUTES
-- ============================================================

UPDATE professionnels SET
  telephone='077 523 39 41',
  cabinet='Centre Pédiatrique Pluridisciplinaire du Chablais',
  adresse='Rue du Pont 5'
WHERE prenom='Célia' AND nom='Moret';

UPDATE professionnels SET telephone='077 415 00 64', adresse='Rue des Remparts 14' WHERE prenom='Sabine' AND nom='Adami';

-- ============================================================
-- NUTRITION / DIÉTÉTICIENNE
-- ============================================================

UPDATE professionnels SET
  telephone='079 900 96 40',
  cabinet='Institut Amaya',
  adresse='Rue du Scex 16'
WHERE prenom='Cinzia' AND nom='Pommaz Cantu';

UPDATE professionnels SET
  specialite='dieteticienne',
  telephone='079 108 66 06',
  cabinet='Essentia',
  adresse='Rue d\'Aoste 9'
WHERE prenom='Céline' AND nom='Lovey';
