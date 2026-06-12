-- Création de la base
CREATE DATABASE IF NOT EXISTS medirdv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE medirdv;

-- --------------------------------------------------------
-- STRUCTURE
-- --------------------------------------------------------

CREATE TABLE roles (
    id_role INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE utilisateurs (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    prenom VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(20),
    photo VARCHAR(255),
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_role INT NOT NULL,
    id_etablissement INT DEFAULT NULL,
    FOREIGN KEY (id_role) REFERENCES roles(id_role)
) ENGINE=InnoDB;

CREATE TABLE patients (
    id_patient INT PRIMARY KEY,
    date_naissance DATE,
    groupe_sanguin VARCHAR(5),
    contact_urgence VARCHAR(100),
    adresse TEXT,
    FOREIGN KEY (id_patient) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE specialites (
    id_specialite INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE villes (
    id_ville INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    pays VARCHAR(100) DEFAULT 'Cameroun'
) ENGINE=InnoDB;

CREATE TABLE etablissements (
    id_etablissement INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(200) NOT NULL,
    type ENUM('Hopital','Clinique','Cabinet','Centre de sante','Laboratoire') NOT NULL,
    description TEXT,
    adresse TEXT,
    telephone VARCHAR(20),
    email VARCHAR(150),
    coord_gps VARCHAR(50),
    horaires TEXT,
    logo VARCHAR(255),
    id_ville INT,
    FOREIGN KEY (id_ville) REFERENCES villes(id_ville)
) ENGINE=InnoDB;

CREATE TABLE medecins (
    id_medecin INT PRIMARY KEY,
    sexe ENUM('M','F'),
    diplomes TEXT,
    experience INT,
    id_specialite INT,
    id_etablissement INT,
    FOREIGN KEY (id_medecin) REFERENCES utilisateurs(id_utilisateur) ON DELETE CASCADE,
    FOREIGN KEY (id_specialite) REFERENCES specialites(id_specialite),
    FOREIGN KEY (id_etablissement) REFERENCES etablissements(id_etablissement)
) ENGINE=InnoDB;

CREATE TABLE services (
    id_service INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150) NOT NULL,
    description TEXT
) ENGINE=InnoDB;

CREATE TABLE etablissement_service (
    id_etablissement INT,
    id_service INT,
    PRIMARY KEY (id_etablissement, id_service),
    FOREIGN KEY (id_etablissement) REFERENCES etablissements(id_etablissement) ON DELETE CASCADE,
    FOREIGN KEY (id_service) REFERENCES services(id_service) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE disponibilites (
    id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
    id_medecin INT NOT NULL,
    jour ENUM('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche'),
    heure_debut TIME,
    heure_fin TIME,
    FOREIGN KEY (id_medecin) REFERENCES medecins(id_medecin) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE rendez_vous (
    id_rdv INT AUTO_INCREMENT PRIMARY KEY,
    reference VARCHAR(20) NOT NULL UNIQUE,
    date_rdv DATE NOT NULL,
    heure_rdv TIME NOT NULL,
    motif TEXT,
    statut ENUM('En attente','Confirmé','Reporté','Annulé','Terminé') DEFAULT 'En attente',
    id_patient INT NOT NULL,
    id_medecin INT NOT NULL,
    id_etablissement INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_patient) REFERENCES patients(id_patient),
    FOREIGN KEY (id_medecin) REFERENCES medecins(id_medecin),
    FOREIGN KEY (id_etablissement) REFERENCES etablissements(id_etablissement)
) ENGINE=InnoDB;

CREATE TABLE qr_codes (
    id_qr INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(255) NOT NULL,
    id_rdv INT UNIQUE,
    FOREIGN KEY (id_rdv) REFERENCES rendez_vous(id_rdv) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE paiements (
    id_paiement INT AUTO_INCREMENT PRIMARY KEY,
    montant DECIMAL(10,2) NOT NULL,
    mode ENUM('Carte bancaire','Mobile Money','Espèces','Autre') NOT NULL,
    date_paiement DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_rdv INT UNIQUE,
    FOREIGN KEY (id_rdv) REFERENCES rendez_vous(id_rdv)
) ENGINE=InnoDB;

CREATE TABLE teleconsultations (
    id_tele INT AUTO_INCREMENT PRIMARY KEY,
    lien VARCHAR(255) NOT NULL,
    date_debut DATETIME,
    duree INT COMMENT 'en minutes',
    id_rdv INT UNIQUE,
    FOREIGN KEY (id_rdv) REFERENCES rendez_vous(id_rdv)
) ENGINE=InnoDB;

CREATE TABLE notifications (
    id_notif INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50),
    contenu TEXT,
    canal ENUM('SMS','Email','WhatsApp'),
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_utilisateur INT,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE langues (
    id_langue INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(5) NOT NULL UNIQUE,
    nom VARCHAR(50)
) ENGINE=InnoDB;

CREATE TABLE traductions (
    id_traduction INT AUTO_INCREMENT PRIMARY KEY,
    cle VARCHAR(100) NOT NULL,
    valeur TEXT NOT NULL,
    id_langue INT NOT NULL,
    FOREIGN KEY (id_langue) REFERENCES langues(id_langue) ON DELETE CASCADE,
    UNIQUE KEY (cle, id_langue)
) ENGINE=InnoDB;

CREATE TABLE blog_articles (
    id_article INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT,
    date_publication DATETIME DEFAULT CURRENT_TIMESTAMP,
    id_auteur INT,
    FOREIGN KEY (id_auteur) REFERENCES utilisateurs(id_utilisateur)
) ENGINE=InnoDB;

CREATE TABLE faq (
    id_faq INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    reponse TEXT,
    ordre INT DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE contacts (
    id_contact INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    email VARCHAR(150),
    sujet VARCHAR(200),
    message TEXT,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE partenaires (
    id_partenaire INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(150),
    logo VARCHAR(255),
    site_web VARCHAR(255)
) ENGINE=InnoDB;

CREATE TABLE historiques_actions (
    id_histo INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT,
    action VARCHAR(255),
    details TEXT,
    date_action DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateurs(id_utilisateur) ON DELETE SET NULL
) ENGINE=InnoDB;

-- --------------------------------------------------------
-- DONNÉES DE BASE
-- --------------------------------------------------------

INSERT INTO roles (libelle) VALUES 
('Super Administrateur'),
('Administrateur Établissement'),
('Médecin'),
('Réceptionniste'),
('Patient');

-- Langues supportées
INSERT INTO langues (code, nom) VALUES 
('fr', 'Français'),
('en', 'English'),
('ar', 'العربية');

-- Ville
INSERT INTO villes (nom, pays) VALUES ('Douala', 'Cameroun');
SET @id_ville = LAST_INSERT_ID();

-- Établissement
INSERT INTO etablissements (nom, type, id_ville) 
VALUES ('Clinique Example', 'Clinique', @id_ville);
SET @id_etablissement = LAST_INSERT_ID();

-- Spécialité
INSERT INTO specialites (nom) VALUES ('Généraliste');
SET @id_specialite = LAST_INSERT_ID();

-- Super Administrateur (mot de passe : password)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role, id_etablissement)
VALUES ('Super', 'Admin', 'admin@medirdv.com', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NULL);

-- Médecin (mot de passe : password)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role, id_etablissement)
VALUES ('Tagne', 'Michel', 'dr.michel@example.com', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 3, @id_etablissement);
SET @id_medecin_utilisateur = LAST_INSERT_ID();

INSERT INTO medecins (id_medecin, sexe, diplomes, experience, id_specialite, id_etablissement)
VALUES (@id_medecin_utilisateur, 'M', 'Docteur en médecine', 10, @id_specialite, @id_etablissement);

-- Disponibilités du médecin (Lundi au Vendredi, 8h-17h)
INSERT INTO disponibilites (id_medecin, jour, heure_debut, heure_fin) VALUES 
(@id_medecin_utilisateur, 'Lundi', '08:00:00', '17:00:00'),
(@id_medecin_utilisateur, 'Mardi', '08:00:00', '17:00:00'),
(@id_medecin_utilisateur, 'Mercredi', '08:00:00', '17:00:00'),
(@id_medecin_utilisateur, 'Jeudi', '08:00:00', '17:00:00'),
(@id_medecin_utilisateur, 'Vendredi', '08:00:00', '17:00:00');

-- Patient de test (mot de passe : password)
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, id_role, id_etablissement)
VALUES ('Dupont', 'Jean', 'patient@example.com', 
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 5, NULL);
SET @id_patient_utilisateur = LAST_INSERT_ID();

INSERT INTO patients (id_patient, date_naissance, groupe_sanguin, contact_urgence, adresse)
VALUES (@id_patient_utilisateur, '1990-05-15', 'O+', 'Marie Dupont - 0601020304', '123 rue des Lilas, Douala');