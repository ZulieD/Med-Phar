create database if not exists medical_database
USE medical_database;

drop table if exists adresse_cabinet;
drop table if exists Medecin;
drop table if exists Patient;
drop table if exists Medicament;
drop table if exists Consultation;
drop table if exists Maladie;
drop table if exists Risque;
drop table if exists Prescription;
drop table if exists Effet_secondaire;
drop table if exists Reaction;

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
    qualite_alimentation int,
    );
    
create table Medicament (
	CODE_CIS int primary key,
    denomination varchar(100),
    forme_pharmaceutique varchar(100),
    voie_administration varchar(100),
    statut_administration varchar(100),
    etat_commercialisation varchar(100),
    statutbdm varchar(50),
    titulaires varchar(100),
    surveillance_renforce varchar(100),
    libelle_presentation varchar(500),
    agrement_collectivite varchar(100),
    denomination_substance varchar(100),
    dosage varchar(50),
    nature_composant varchar(100),
    libelle_generique varchar(500),
    type_generique varchar(50),
    condition_prescription varchar (500)
    );
create table Consultation (
    id int primary key,
    id_medecin int,
    id_patient int, 
    id_maladie int, 
    foreign key (id_medecin) references Medecin(id),
    foreign key (id_patient) references Patient(id),
    foreign key (id_maladie) references Maladie(id)
)   
create table Maladie (
	id int primary key, 
    symptome varchar(500),
    nom varchar(500),
    debut_prise date,
    fin_prise date,
    maladie_correle varchar(500),
    id_medicament int,
    foreign key (id_medicament) references Medicament(CIS)
    );  
    
create table Risque(
    id int primary key,
    symptome varchar(50),
    debut date,
    fin date, 
    cause varchar(100),
    hereditaire int,
    id_patient int,
    foreign key (id_patient) references Patient(id)
)

create table Prescription(
    id int primary key,
    id_maladie int,
    id_risque int,
    code_cis int,
    foreign key (id_maladie) references Maladie(id),
    foreign key (id_risque) references Risque(id),
    foreign key (code_cis) references Medicament(CODE_CIS)
)

create table Effet_secondaire(
    id int primary key,
    nom varchar(50),
    durée varchar(50),
    frequence varchar(100)
)

create table Reaction(
    id int primary key,
    code_cis int, 
    id_patient int,
    id_effet_secondaire int, 
    foreign key (code_cis) references Medicament(CODE_CIS),
    foreign key (id_patient) references Patient(id),
    foreign key (id_effet_secondaire) references Effet_secondaire(id)
)

INSERT INTO adresse_cabinet (id, numero, rue, ville, Departement, Pays)
VALUES 
(1, 123, 'Rue de la Paix', 'Paris', 'Paris', 'France'),
(2, 456, 'Boulevard des Roses', 'Lyon', 'Rhône', 'France'),
(3, 789, 'Avenue du Soleil', 'Marseille', 'Bouches-du-Rhône', 'France');

INSERT INTO Medecin (id, prenom, nom, date_naissance, adresse_email, mot_de_passe, specialite, id_adresse)
VALUES 
(1, 'Jean', 'Dupont', '1980-05-15', 'jean.dupont@example.com', 'motdepasse123', 'Cardiologue', 1),
(2, 'Marie', 'Martin', '1975-10-20', 'marie.martin@example.com', 'mdp456', 'Pédiatre', 2),
(3, 'Luc', 'Bernard', '1988-03-08', 'luc.bernard@example.com', 'mdp789', 'Dermatologue', 3);

INSERT INTO Patient (id, prenom, nom, date_naissance, sexe, contraception, poids, taille, allergie, activite_metier, risque_metier, activite_quotidienne, qualite_alimentation)
VALUES 
(1, 'Alice', 'Dubois', '1990-03-25', 'F', 'Pilule', 60, 1.65, 'Penicilline', 1, 0, 2, 3),
(2, 'Thomas', 'Lefevre', '1985-08-12', 'M', 'Aucune', 80, 1.8, NULL, 2, 1, 3, 2),
(3, 'Sophie', 'Moreau', '1976-11-08', 'F', 'DIU', 65, 1.7, 'Acariens', 3, 0, 1, 2),
(4, 'Pierre', 'Girard', '1992-07-31', 'M', 'Aucune', 70, 1.75, 'Aucune', 2, 1, 2, 1),
(5, 'Camille', 'Andre', '1983-04-17', 'F', 'Aucune', 55, 1.6, 'Aucune', 1, 0, 2, 3),
(6, 'Nicolas', 'Roux', '1995-09-22', 'M', 'Pilule', 75, 1.78, 'Aucune', 3, 1, 3, 2),
(7, 'Emma', 'Martin', '1979-12-04', 'F', 'Implant', 68, 1.68, 'Aucune', 2, 1, 3, 3),
(8, 'Julien', 'Leroy', '1988-02-19', 'M', 'Aucune', 82, 1.85, 'Noix de cajou', 1, 0, 2, 1);

INSERT INTO Consultation (id, id_medecin, id_patient, id_maladie)
VALUES 
(1, 1, 1, 1),
(2, 2, 2, 2),
(3, 3, 3, 3),
(4, 1, 4, 1),
(5, 2, 5, 2);

INSERT INTO Maladie (id, symptome, nom, debut_prise, fin_prise, maladie_correle, id_medicament)
VALUES 
(1, 'Fièvre, douleurs musculaires', 'Grippe', '2024-01-10', '2024-01-20', NULL, 123456),
(2, 'Éruption cutanée, démangeaisons', 'Allergie alimentaire', '2023-06-15', NULL, 'Intolérance au gluten', 789012),
(3, 'Acné, peau sèche', 'Eczéma', '2024-04-05', NULL, NULL, 345678),
(4, 'Douleurs articulaires, raideur', 'Arthrite', '2023-11-20', NULL, NULL, 901234),
(5, 'Saignements, fatigue', 'Leucémie', '2024-02-28', NULL, NULL, 567890);

INSERT INTO Risque (id, symptome, debut, fin, cause, hereditaire, id_patient)
VALUES 
(1, 'Hypertension artérielle', '2022-05-01', NULL, 'père', 1, 1),
(2, 'Diabète de type 2', '2023-02-10', NULL, 'Surpoids', 1, 2),
(3, 'Cancer du sein', '2024-04-15', NULL, 'grand-mere', 1, 3),
(4, 'Maladies cardiovasculaires', '2023-10-02', NULL, 'Hypertension artérielle', 1, 4),
(5, 'Obésité', '2024-03-20', NULL, 'Habitudes alimentaires', 0, 5);

INSERT INTO Prescription (id, id_maladie, id_risque, code_cis)
VALUES 
(1, 1, 1, 123456),
(2, 2, 2, 789012),
(3, 3, 3, 345678),
(4, 4, 4, 901234),
(5, 5, 5, 567890);

INSERT INTO Effet_secondaire (id, nom, durée, frequence)
VALUES 
(1, 'Maux de tête', '1 jour', 'Fréquent'),
(2, 'Nausées', '3 jours', 'Occasionnel'),
(3, 'Somnolence', '2 jours', 'Occasionnel'),
(4, 'Douleurs musculaires', '4 jours', 'Fréquent'),
(5, 'Sécheresse de la bouche', '2 jours', 'Rare');

