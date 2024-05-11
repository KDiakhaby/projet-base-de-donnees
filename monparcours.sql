-- Création de la table 'cursus' pour stocker les informations sur les cursus
CREATE TABLE cursus (
  id SERIAL PRIMARY KEY, -- Identifiant unique auto-incrémenté
  nom varchar(100) NOT NULL -- Nom du cursus
);

-- Insertion de données dans la table 'cursus'
INSERT INTO cursus (nom) VALUES
('Licence 1'),
('Licence 2'),
('Licence 3');

-- Création de la table 'programmes' pour stocker les informations sur les programmes
CREATE TABLE programmes (
  id SERIAL PRIMARY KEY, -- Identifiant unique auto-incrémenté
  nom varchar(100) NOT NULL, -- Nom du programme
  contenu varchar(255) NOT NULL, -- Contenu du programme
  cursus_category int NOT NULL, -- Catégorie du cursus associé
  date date NOT NULL, -- Date du programme
  image varchar(255) NOT NULL, -- Chemin de l'image du programme
  CONSTRAINT fk_programmes_cursus_category FOREIGN KEY (cursus_category) REFERENCES cursus (id) ON DELETE CASCADE ON UPDATE CASCADE -- Contrainte de clé étrangère pour lier à la table 'cursus'
);

-- Insertion de données dans la table 'programmes'
INSERT INTO programmes (nom, contenu, cursus_category, date, image) VALUES
(1, 'Cours L1', 'Unités: UE 1, UE 2, UE 3, UE 4, UE 5, UE 6, UE 7', 1, '2020-09-21', 'imgL1'),
(2, 'Cours L2', 'Unités: UE 8, UE 9, UE 10, UE 11, UE 12, UE 13, UE 14', 2, '2021-07-19', 'imgL2'),
(3, 'Cours L3', 'Unités: UE 15, UE 16, UE 17, UE 18, UE 19, UE 20', 3, '2023-09-18', 'imgL3');

-- Changement de la colonne 'cours' à 'contenu' dans la table 'programmes'
-- ALTER TABLE programmes RENAME COLUMN cours TO contenu;

-- Mise à jour du contenu pour les programmes spécifiques
-- UPDATE programmes SET contenu = 'Unités: UE 8, UE 9, UE 10, UE 11, UE 12, UE 13, UE 14' WHERE id = 2;
-- UPDATE programmes SET contenu = 'Unités: UE 15, UE 16, UE 17, UE 18, UE 19, UE 20' WHERE id = 3;
