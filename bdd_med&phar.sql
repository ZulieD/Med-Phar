create table adresse_cabinet(
	id int primary key, 
    numero int, 
    rue varchar(50),
    ville varchar(50),
    Departement varchar(50),
    Pays varchar(50)
    );


create table Medecin ( 
	id INT PRIMARY key, 
    prenom varchar(50), 
    nom varchar(50),
    date_naissance date,
    adresse_email varchar(50),
    mot_de_passe varchar(50),
    specialite varchar(100),
    id_adresse int,
    FOREIGN KEY (id_adresse) REFERENCES adresse_cabinet(id)
    );
    
create table patient( 
	id_patient int primary key, 
    prenom varchar(50), 
    nom varchar(50),
    date_naissance date,
    sexe char,
    contraception varchar(50),
    poids int,
    taille float,
    allergie varchar(500),
    activite_metier int,
    risque_metier int, 
    activite_quotidienne int,
    qualite_alimentation int
    );
create table Maladie (
	id int primary key, 
    symptome varchar(500),
    nom varchar(500),
    debut_prise date,
    fin_prise date,
    maladie_correle varchar(500),
    hereditaire int,
    cause varchar(100),
    id_medicament int
    );  
    
create table Consultation (
    id int primary key,
    date_consult date,
    id_medecin int,
    id_patient int, 
    id_maladie int, 
    foreign key (id_medecin) references Medecin(id),
    foreign key (id_patient) references Patient(id_patient),
    foreign key (id_maladie) references Maladie(id)
) ;

create table Effet_secondaire(
    id int primary key,
    nom varchar(50),
    duree varchar(50),
    frequence varchar(100)
);
create table Reaction(
    id int primary key,
    id_medicament int, 
    id_patient int,
    id_effet_secondaire int,
    foreign key (id_patient) references Patient(id_patient),
    foreign key (id_effet_secondaire) references Effet_secondaire(id)
);

INSERT INTO adresse_cabinet (id, numero, rue, ville, Departement, Pays) VALUES
(1, 10, 'Rue des Lilas', 'Paris', 'Paris', 'France'),
(2, 5, 'Avenue du Soleil', 'Marseille', 'Bouches-du-Rhône', 'France'),
(3, 20, 'Place de la Mairie', 'Lyon', 'Rhône', 'France');


INSERT INTO Medecin (id, prenom, nom, date_naissance, adresse_email, mot_de_passe, specialite, id_adresse) VALUES
(1, 'Jean', 'Dupont', '1980-05-15', 'jean.dupont@example.com', 'motdepasse123', 'Cardiologue', 1),
(2, 'Marie', 'Martin', '1975-08-22', 'marie.martin@example.com', 'mdp456', 'Dermatologue', 2),
(3, 'Pierre', 'Lefevre', '1982-11-10', 'pierre.lefevre@example.com', 'secret789', 'Pédiatre', 3);

INSERT INTO patient (id_patient, prenom, nom, date_naissance, sexe, contraception, poids, taille, allergie, activite_metier, risque_metier, activite_quotidienne, qualite_alimentation) VALUES
(1, 'Alice', 'Dubois', '1990-03-25', 'F', 'Pilule', 60, 1.65, 'Aucune', 1, 2, 3, 4),
(2, 'Thomas', 'Lemoine', '1985-07-12', 'M', 'Stérilet', 75, 1.78, 'Penicilline', 2, 3, 4, 5),
(3, 'Sophie', 'Garcia', '1988-09-18', 'F', 'Aucune', 55, 1.70, 'Aspirine', 3, 4, 5, 3),
(4, 'Luc', 'Robert', '1977-12-05', 'M', 'Préservatif', 80, 1.80, 'Aucune', 4, 5, 2, 4),
(5, 'Julie', 'Bertrand', '1995-02-10', 'F', 'Aucune', 50, 1.60, 'Acariens', 5, 1, 1, 2);

INSERT INTO Maladie (id, symptome, nom, debut_prise, fin_prise, maladie_correle, hereditaire, cause, id_medicament) VALUES
(1, 'Douleurs thoraciques, essoufflement', 'Cardiopathie', '2020-01-15', NULL, NULL, 1, 'Hypertension', 1),
(2, 'Éruption cutanée, démangeaisons', 'Dermatite', '2019-08-10', NULL, 'Psoriasis', 0, 'Allergies alimentaires', 2),
(3, 'Fièvre, toux persistante', 'Bronchite', '2023-02-20', NULL, NULL, 0, 'Infection virale', 3),
(4, 'Douleurs abdominales, nausées', 'Gastrite', '2022-11-05', NULL, NULL, 1, 'Mauvaise alimentation', 4),
(5, 'Maux de tête fréquents, fatigue', 'Migraine', '2021-04-30', NULL, NULL, 0, 'Stress', 5);

INSERT INTO Consultation (id, date_consult, id_medecin, id_patient, id_maladie) VALUES
(1, '2024-06-10', 1, 1, 1),
(2, '2024-06-12', 2, 2, 2),
(3, '2024-06-14', 3, 3, 3),
(4, '2024-06-15', 1, 4, 4),
(5, '2024-06-16', 2, 5, 5);

INSERT INTO Effet_secondaire (id, nom, duree, frequence) VALUES
(1, 'Nausées', '1-2 jours', 'Fréquent'),
(2, 'Somnolence', 'quelques heures', 'Occasionnel'),
(3, 'Réaction allergique', 'variable', 'Rare'),
(4, 'Vertiges', 'quelques minutes à heures', 'Occasionnel');

INSERT INTO Reaction (id, id_medicament, id_patient, id_effet_secondaire) VALUES
(1, 1, 1, 1),  -- Patient 1 a eu l'effet secondaire 'Nausées'
(2, 2, 3, 2),  -- Patient 3 a eu l'effet secondaire 'Somnolence'
(3, 3, 5, 3),  -- Patient 5 a eu l'effet secondaire 'Réaction allergique'
(4, 4, 2, 4);  -- Patient 2 a eu l'effet secondaire 'Vertiges'


