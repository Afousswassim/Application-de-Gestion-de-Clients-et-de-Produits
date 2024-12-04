
create table client 
(
   idclient            integer          auto_increment not null,
   nom                  char(25)                       not null,
   prenom              char(25)                       null,
   constraint PK_CLIENT primary key (idclient)
);




create table produit 
(
   idproduit            integer         auto_increment not null,
   libelle              char(25)                       not null,
   prix                 numeric(8,2)                   not null,
   quantite             integer                        not null,
   constraint PK_PRODUIT primary key (idproduit)
);

create table admin
(
    idadmin             integer                 not null,
    utilisateur         varchar(50)             not null,
    motdepasse          varchar(50)             not null
);
-- Insérer des données dans la table client
INSERT INTO client (nom, prenom) VALUES
                                     ('El Othmani', 'Mohammed'),
                                     ('Benzakour', 'Fatima'),
                                     ('El Idrissi', 'Omar'),
                                     ('Benmoussa', 'Amina'),
                                     ('El Kharraz', 'Youssef'),
                                     ('Ouazzani', 'Salma'),
                                     ('Raji', 'Ayoub'),
                                     ('Hassani', 'Ikram'),
                                     ('El Fassi', 'Rachid'),
                                     ('Berrada', 'Meriem');


insert into admin(idadmin,utilisateur,motdepasse) values(1,"admin",sha1("admin"));


-- Insérer des données dans la table produit avec des produits électroniques
INSERT INTO produit (libelle, prix, quantite) VALUES
                                                  ('Smartphone', 2500.00, 50),
                                                  ('Ordinateur Portable', 7500.00, 30),
                                                  ('Tablette', 3200.00, 40),
                                                  ('Casque Audio', 500.00, 100),
                                                  ('Télévision LED', 4000.00, 20),
                                                  ('Imprimante', 1200.00, 15),
                                                  ('Clé USB 64GB', 150.00, 200),
                                                  ('Disque Dur Externe 1TB', 650.00, 50),
                                                  ('Caméra de Surveillance', 850.00, 25),
                                                  ('Chargeur Universel', 100.00, 150);



