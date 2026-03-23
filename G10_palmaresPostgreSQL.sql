-- Entité


-- Création de la table G10_Course

CREATE TABLE IF NOT EXISTS G10_Course (
    course_id SERIAL PRIMARY KEY,
    course_nom VARCHAR(50) NOT NULL CHECK (course_nom IN ('Tour de France', 'Giro d''Italie', 'Vuelta d''Espagne')),
    course_pays VARCHAR(50) NOT NULL
);



-- Création de la table G10_Coureur

CREATE TABLE IF NOT EXISTS G10_Coureur (
    coureur_id SERIAL PRIMARY KEY,
    coureur_nom VARCHAR(50) NOT NULL,
    coureur_prenom VARCHAR(50) NOT NULL,
    nationalite VARCHAR(50) NOT NULL,
    nom_equipe VARCHAR(50),
    date_naissance DATE NOT NULL
);



-- Création de la table G10_Edition

CREATE TABLE IF NOT EXISTS G10_Edition (
    edition_id SERIAL PRIMARY KEY,
    edi_dateDebut DATE NOT NULL,
    edi_dateFin DATE NOT NULL,
    edi_villeDebut VARCHAR(50) NOT NULL,
    edi_villeFin VARCHAR(50) NOT NULL,
    course_id INT,
    FOREIGN KEY (course_id) REFERENCES G10_Course(course_id)
);



-- Association

-- Création de la table G10_Classer

CREATE TABLE IF NOT EXISTS G10_Classer (
    num_Classement INT NOT NULL CHECK (num_Classement BETWEEN 1 AND 3),
    distance_parcourue FLOAT NOT NULL CHECK (distance_parcourue > 0) ,
    temps INT,
    coureur_id INT,
    edition_id INT,
    PRIMARY KEY(coureur_id, edition_id),
    FOREIGN KEY (coureur_id) REFERENCES G10_Coureur(coureur_id),
    FOREIGN KEY (edition_id) REFERENCES G10_Edition(edition_id)
);




-- Insertion des données dans la table G10_Course

INSERT INTO G10_Course (course_nom, course_pays) 
VALUES 
    ('Tour de France', 'France'),
    ('Giro d''Italie', 'Italie'),
    ('Vuelta d''Espagne', 'Espagne');
    
    
    
    
    

-- Insertion des données dans la table G10_Edition

INSERT INTO G10_Edition (course_id, edi_dateDebut, edi_dateFin, edi_villeDebut, edi_villeFin)
VALUES 
-- Tour de France 
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2015-07-04', '2015-07-26', 'Utrecht', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2016-07-02', '2016-07-24', 'Mont-Saint-Michel', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2017-07-01', '2017-07-23', 'Düsseldorf', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2018-07-07', '2018-07-29', 'Noirmoutier-en-l''Île', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2019-07-06', '2019-07-28', 'Brussels', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2020-08-29', '2020-09-20', 'Nice', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2021-06-26', '2021-07-18', 'Brest', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2022-07-01', '2022-07-24', 'Copenhagen', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2023-07-01', '2023-07-23', 'Bilbao', 'Paris'),
    ((SELECT course_id FROM G10_Course WHERE course_nom ='Tour de France'), '2024-06-29', '2024-07-21', 'Florence', 'Nice'),
     
  -- Giro d'Italie
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2015-05-09', '2015-05-31', 'San Lorenzo al Mare','Milan'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2016-05-06', '2016-05-29', 'Apeldoorn', 'Turin'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2017-05-05', '2017-05-28', 'Alghero', 'Milan'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2018-05-04', '2018-05-27', 'Jerusalem', 'Rome'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2019-05-11', '2019-06-02', 'Bologna', 'Verona'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2020-10-03', '2020-10-25', 'Monreale', 'Milan'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2021-05-08', '2021-05-30', 'Turin', 'Milan'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2022-05-06', '2022-05-29', 'Budapest', 'Verona'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2023-05-06', '2023-05-28', 'Pescara', 'Rome'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Giro d''Italie'), '2024-05-04', '2024-05-26', 'Venise', 'Rome'),
    -- vuelta d'Espagne
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2015-08-22', '2015-09-13', 'Marbella', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2016-08-20', '2016-09-11', 'Ourense', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2017-08-19', '2017-09-10', 'Nîmes', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2018-08-25', '2018-09-16', 'Málaga', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'),'2019-08-24','2019-09-15','Salinas de Torrevieja','Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2020-10-20', '2020-11-08', 'Irún', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'),'2021-08-14','2021-09-05','Burgos','Santiago de Compostela'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2022-08-19', '2022-09-11', 'Utrecht', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2023-08-26', '2023-09-17', 'Barcelona', 'Madrid'),
     ((SELECT course_id FROM G10_Course WHERE course_nom ='Vuelta d''Espagne'), '2024-08-17', '2024-09-08', 'Lisbonne', 'Madrid');





-- Insertion des données dans la table G10_Coureur

INSERT INTO G10_Coureur (coureur_nom, coureur_prenom, nationalite, nom_equipe, date_naissance)
VALUES
   ('Pogacar', 'Tadej', 'Slovène', 'UAE Team Emirates', '1998-09-21'),
      ('Vingegaard', 'Jonas', 'danoise', 'Team Visma', '1996-12-10'),
      ('Evenepoel', 'Remco', 'belge', 'Soudal Quick-Step','2000-01-25'),
      ('Yates', 'Adam', 'britannique', 'UAE Team Emirates', '1992-08-07'), 
      ('Thomas', 'Geraint','britannique', 'Ineos Grenadiers', '1986-05-25'),
      ('Carapaz','Richard','équatorienne','EF Education-EasyPost','1993-05-29'),
      ('Roglic', 'Primož', 'slovène', 'Jumbo-Visma', '1989-10-29'),
      ('Porte','Richie','australienne','Trek-Segafredo','1985-01-30'),
      ('Bernal','Egan','colombienne','Team Ineos','1997-01-13'),
      ('Kruijswijk','Steven','néerlandaise','Team Jumbo-Visma','1987-06-07'), 
      ('Martinez','Daniel','colombienne','Bora-Hansgrohe','1996-04-25'),  
      ('Almeida','João','portugais','UAE Emirates','1998-08-05'), 
      ('Hindley','Jai','australienne','Bora-Hansgrohe','1996-05-05'), 
      ('Landa','Mikel','espagnole','Bahrain Victorious','1989-12-13'),
      ('Caruso','Damiano','italienne','Bahrain Victorious','1987-10-12'),
      ('Yates','Simon','britannique','BikeExchange','1992-08-07'),
      ('Geoghegan Hart','Tao','britannique','Ineos Grenadiers','1995-03-30'),
      ('Kelderman','Wilco','néerlandaise','Team Sunweb','1991-03-25'),
      ('Nibali','Vincenzo','italienne','Bahrain-Merida','1984-11-14'),
      ('O''Connor','Ben Alexander','australien','Decathlon-AG2R La Mondiale','1995-11-25'), 
      ('Mas','Enric','espagnol','Movistar','1995-01-07'),     
      ('Kuss','Sepp','américain','Jumbo-Visma','1994-09-13'),      
      ('Ayuso','Juan','espagnol','UAE Team Emirates','2002-09-16'),  
      ('Haig','Jack','australien','Bahrain Victorious','1993-09-06'),
      ('Carthy','Hugh John','britannique','EF Pro Cycling','1994-07-09'), 
      ('Valverde','Alejandro','espagnole','Movistar','1980-04-25'),
      ('Dumoulin','Tom','néerlandaise','Sunweb','1990-11-11'),
      ('Froome','Christopher','britannique ','Sky','1985-05-20'),
      ('Uran','Rigoberto','colombienne','Cannondale-Drapac','1987-01-26'),
      ('Bardet','Romain','française','AG2R La Mondiale','1990-11-09'),
      ('Quintana','Nairo','colombienne','Movistar Team','1990-02-04'),
      ('Zakarin','Ilnur','russe','Katusha-Alpecin','1989-09-15'),
      ('Aru','Fabio','italienne','Astana','1990-07-03'),
      ('Rodriguez','Joaquim','espagnole','Katusha','1979-05-12'),
      ('Majka','Rafal','polonaise','Tinkoff-Saxo','1989-09-12'),
      ('Lopez','Miguel Angel', 'colombienne','Astana', '1994-02-04'),
      ('Contador', 'Alberto', 'espagnole', 'Trek-Segafredo','1992-12-06'),   
      ('Chaves', 'Esteban', 'colombienne', 'Mitchelton-Scott', '1990-01-17');
      
      
      

-- Insertion des données dans la table G10_Classer

INSERT INTO G10_Classer (num_Classement, distance_parcourue, temps, coureur_id, edition_id)
VALUES

-- Tour de France

-- 2024
(1,3498,7200,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Pogacar'),(SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2024-06-29')),
(2, 3490, 7250,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Vingegaard'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2024-06-29')),
(3, 3480, 7300,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Evenepoel'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2024-06-29')),

-- 2023
(1, 3405.6, 7150,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Vingegaard'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2023-07-01')),
(2,3405, 7200,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Pogacar'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2023-07-01')),
(3, 3400, 7300,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Yates' AND coureur_prenom='Adam'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2023-07-01')),

-- 2022
(1, 3349.9, 7250,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Vingegaard'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2022-07-01')),
(2, 3348, 7300,(SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Pogacar'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2022-07-01')),
(3, 3344.5, 7350, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Thomas'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2022-07-01')),

-- 2021
(1, 3414.4, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Pogacar'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2021-06-26')),
(2, 3410.9, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Vingegaard'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2021-06-26')),
(3, 3410, 7350, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Carapaz'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2021-06-26')),

-- 2020
(1, 3484.2, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Pogacar'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2020-08-29')),
(2, 3483, 7350, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2020-08-29')),
(3, 3480.3, 7400, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Porte'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2020-08-29')),

-- 2019
(1, 3365.8, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Bernal'),(SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2019-07-06')),
(2, 3365.3, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Thomas'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2019-07-06')),
(3, 3365, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom ='Kruijswijk'),(SELECT edition_id FROM G10_Edition WHERE course_id =1 AND edi_dateDebut = '2019-07-06')),

-- 2018
(1,3351, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Thomas'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2018-07-07')),
(2,3351, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Dumoulin'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2018-07-07')),
(3,3351, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2018-07-07')),

-- 2017
(1, 3540, 7500, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2017-07-01')),
(2, 3540, 7400, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Uran'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2017-07-01')),
(3, 3540, 7350, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Bardet'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2017-07-01')),

-- 2016
(1, 3529, 7400, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2016-07-02')),
(2, 3529, 7350, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Bardet'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2016-07-02')),
(3,3529, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Quintana'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2016-07-02')),

-- 2015
(1, 3360, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2015-07-04')),
(2, 3360, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Quintana'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2015-07-04')),
(3, 3360, 7150, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Valverde'), (SELECT edition_id FROM G10_Edition WHERE course_id = 1 AND edi_dateDebut = '2015-07-04')),

-- Giro d'Italie

-- 2024
(1, 3317.5, 8400, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Pogacar'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2024-05-04')),
(2, 3316, 8500, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Martinez'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2024-05-04')),
(3, 3315.6, 8600, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Thomas'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2024-05-04')),
    
-- 2023
(1, 3357.8, 8480, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2023-05-06')),
(2, 3357, 8500, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Thomas'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2023-05-06')),
(3, 3356, 8600, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Almeida'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2023-05-06')),
 
 -- 2022
(1, 3499.6, 8550, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Hindley'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2022-05-06')),
(2, 3497, 8580, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Carapaz'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2022-05-06')),
(3, 3495.5, 8600, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Landa'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2022-05-06')),


-- 2021
(1, 3410.9, 8600, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Bernal'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2021-05-08')),
(2, 3410.1, 8610, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Caruso'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2021-05-08')),
(3, 3409, 8620, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Yates' AND coureur_prenom='Simon'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2021-05-08')),
    
-- 2020
(1, 3497.9, 8580, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Geoghegan Hart'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2020-10-03')),
(2, 3497.4, 8600, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Hindley'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2020-10-03')),
(3, 3497.1, 8630, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Kelderman'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2020-10-03')),


-- 2019
(1, 3486.5, 8500, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Carapaz'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2019-05-11')),
(2, 3486.3, 8510, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Nibali'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2019-05-11')),
(3, 3486, 8530, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2019-05-11')),
    
-- 2018
 (1, 3562.9, 8513, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2018-05-04')),
(2, 3562.9, 8460, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Dumoulin'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2018-05-04')),
(3,3562.9, 8450, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Lopez'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2018-05-04')),

-- 2017
(1, 3609.1, 8476, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Dumoulin'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2017-05-05')),
(2, 3609.1, 8420, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Quintana'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2017-05-05')),
(3, 3609.1, 8410, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Nibali'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2017-05-05')),

-- 2016
(1, 3383, 8424, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Nibali'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2016-05-06')),
(2, 3383, 8400, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Chaves'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2016-05-06')),
(3, 3383, 8380, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Valverde'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2016-05-06')),

-- 2015
(1, 3481.8, 8926, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Contador'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2015-05-09')),
(2, 3481.8, 8900, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Aru'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2015-05-09')),
(3, 3481.8, 8850, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Landa'), (SELECT edition_id FROM G10_Edition WHERE course_id = 2 AND edi_dateDebut = '2015-05-09')),


-- Vuelta d'Espagne

-- 2024
(1, 3304, 8450, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2024-08-17')),
(2, 3303.8, 8465, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'O''Connor'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2024-08-17')),
(3, 3303.1, 8490, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Mas'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2024-08-17')),

-- 2023
(1, 3156, 7000, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Kuss'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2023-08-26')),
(2, 3156, 7050, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Vingegaard'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2023-08-26')),
(3, 3156, 7100, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2023-08-26')),


-- 2022
(1, 3283, 8505, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Evenepoel'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2022-08-19')),
(2, 3283, 8525, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Mas'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2022-08-19')),
(3, 3283, 8540, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Ayuso'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2022-08-19')),


-- 2021
(1, 3417, 8500, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2021-08-14')),
(2, 3417, 8520, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Mas'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2021-08-14')),
(3, 3417, 8540, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Haig'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2021-08-14')),

-- 2020
(1, 2846.9, 6980, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2020-10-20')),
(2, 2846.8, 6991, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Carapaz'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2020-10-20')),
(3, 2846.6, 7002, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Carthy'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2020-10-20')),

-- 2019
(1, 3272.2, 8440, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Roglic'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2019-08-24')),
(2, 3272.1, 8450, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Valverde'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2019-08-24')),
(3, 3272, 8470, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Pogacar'), 
    (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2019-08-24')),
    
-- 2018
 (1, 3271.4, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Yates' AND coureur_prenom='Simon'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2018-08-25')),
(2, 3271.4, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Mas'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2018-08-25')),
(3,3271.4, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Lopez'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2018-08-25')),

-- 2017
(1, 3297.7, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2017-08-19')),
(2,3297.7, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Nibali'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2017-08-19')),
(3,3297.7, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Zakarin'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2017-08-19')),

-- 2016
(1,3277.3 , 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Quintana'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2016-08-20')),
(2, 3277.3, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Froome'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2016-08-20')),
(3, 3277.3, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Chaves'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2016-08-20')),

-- 2015
(1, 3376.4, 7200, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Aru'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2015-08-22')),
(2, 3376.4, 7250, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Rodriguez'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2015-08-22')),
(3, 3376.4, 7300, (SELECT coureur_id FROM G10_Coureur WHERE coureur_nom = 'Majka'), (SELECT edition_id FROM G10_Edition WHERE course_id = 3 AND edi_dateDebut = '2015-08-22'));

