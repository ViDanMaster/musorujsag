DROP DATABASE IF EXISTS musorujsag;
DROP USER IF EXISTS 'musorujsag'@'%';

CREATE DATABASE musorujsag;
USE musorujsag; 

CREATE TABLE Admin (
    name VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    email VARCHAR(100) PRIMARY KEY NOT NULL,
    last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Channel (
    name VARCHAR(100) PRIMARY KEY,
    category VARCHAR(50),
    description TEXT
);

CREATE TABLE Performer (
    name VARCHAR(100) PRIMARY KEY,
    birth_date DATE,
    nationality VARCHAR(50),
    occupation VARCHAR(50)
);

CREATE TABLE Episode (
    name VARCHAR(100),
    episode_number INT,
    summary TEXT,
    PRIMARY KEY (name, episode_number)
);

CREATE TABLE Show_Performer (
    show_name VARCHAR(100),
    episode_number INT,
    performer_name VARCHAR(100),
    PRIMARY KEY (show_name, episode_number, performer_name),
    FOREIGN KEY (show_name, episode_number) REFERENCES Episode(name, episode_number) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (performer_name) REFERENCES Performer(name) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE Projection (
    show_name VARCHAR(100),
    episode_number INT,
    channel_name VARCHAR(100),
    air_datetime DATETIME,
    PRIMARY KEY (show_name, episode_number, channel_name, air_datetime),
    FOREIGN KEY (show_name, episode_number) REFERENCES Episode(name, episode_number) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (channel_name) REFERENCES Channel(name) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE USER 'musorujsag'@'%' IDENTIFIED BY 'erosjelszo';

GRANT SELECT, INSERT, UPDATE, DELETE ON musorujsag.* TO 'musorujsag'@'%';

INSERT INTO Channel (name, category, description) VALUES
('Csatorna1', 'Kereskedelmi', 'Hírcsatorna napi frissítésekkel'),
('Csatorna2', 'Szórakoztatás', 'Szórakoztató műsorok és események'),
('Csatorna3', 'Kereskedelmi', 'Dokumentumfilmek különböző témákról'),
('Csatorna4', 'Kereskedelmi', 'Élő sportközvetítések és elemzések'),
('Csatorna5', 'Filmek', '24/7 filmcsatorna'),
('Csatorna6', 'Zene', 'Zenei videók és élő fellépések'),
('Csatorna7', 'Főzés', 'Főzőműsorok és kulináris kalandok'),
('Csatorna8', 'Tudomány', 'Tudományos felfedezések és oktató tartalmak'),
('Csatorna9', 'Divat', 'Divatbemutatók és stílustanácsok'),
('Csatorna10', 'Technológia', 'Legújabb technológiai és gadget hírek');

INSERT INTO Performer (name, birth_date, nationality, occupation) VALUES
('Szereplő1', '1990-05-15', 'Amerikai', 'Szinész'),
('Szereplő2', '1985-12-03', 'Brit', 'Énekes'),
('Szereplő3', '1978-08-20', 'Francia', 'Séf'),
('Szereplő4', '1995-02-10', 'Japán', 'Sportoló'),
('Szereplő5', '1980-06-25', 'Kanadai', 'Rendező'),
('Szereplő6', '1992-04-18', 'Olasz', 'Modell'),
('Szereplő7', '1987-09-30', 'Brazíliai', 'Tudós'),
('Szereplő8', '1983-11-12', 'Spanyol', 'Divattervező'),
('Szereplő9', '1975-07-08', 'Ausztrál', 'Zenész'),
('Szereplő10', '1989-03-24', 'Német', 'Technológus');

INSERT INTO Episode (name, episode_number, summary) VALUES
('Műsor1', 1, 'Izgalmas show első rész'),
('Műsor1', 2, 'Drámai show második rész'),
('Műsor2', 1, 'Vicces show első rész'),
('Műsor2', 2, 'Szórakoztató show második rész'),
('Műsor3', 1, 'Dokumentumfilm sorozat bemutatkozás'),
('Műsor3', 2, 'Észlelő dokumentumfilm második rész'),
('Műsor4', 1, 'Sportesemény élő közvetítése - 1. rész'),
('Műsor4', 2, 'Sportesemény élő közvetítése - 2. rész'),
('Műsor5', 1, 'Blockbuster film maraton - Film 1'),
('Műsor5', 2, 'Blockbuster film maraton - Film 2'),
('Műsor6', 1, 'Exkluzív zenei koncert - 1. rész'),
('Műsor6', 2, 'Exkluzív zenei koncert - 2. rész'),
('Műsor7', 1, 'Kulináris verseny - 1. forduló'),
('Műsor7', 2, 'Kulináris verseny - 2. forduló'),
('Műsor8', 1, 'Beszélgetés a legújabb tudományos eredményekről - 1. rész'),
('Műsor8', 2, 'Beszélgetés a legújabb tudományos eredményekről - 2. rész'),
('Műsor9', 1, 'Kifutó divatbemutató - Évadnyitó'),
('Műsor9', 2, 'Kifutó divatbemutató - Évadzáró'),
('Műsor10', 1, 'Tech Expó Epizód 1'),
('Műsor10', 2, 'Tech Expó Epizód 2');

INSERT INTO Show_Performer (show_name, episode_number, performer_name) VALUES
('Műsor1', 1, 'Szereplő1'),
('Műsor1', 2, 'Szereplő2'),
('Műsor2', 1, 'Szereplő3'),
('Műsor2', 2, 'Szereplő4'),
('Műsor3', 1, 'Szereplő5'),
('Műsor3', 2, 'Szereplő6'),
('Műsor4', 1, 'Szereplő7'),
('Műsor4', 2, 'Szereplő8'),
('Műsor5', 1, 'Szereplő9'),
('Műsor5', 2, 'Szereplő10'),
('Műsor6', 1, 'Szereplő1'),
('Műsor6', 2, 'Szereplő2'),
('Műsor7', 1, 'Szereplő3'),
('Műsor7', 2, 'Szereplő4'),
('Műsor8', 1, 'Szereplő5'),
('Műsor8', 2, 'Szereplő6'),
('Műsor9', 1, 'Szereplő7'),
('Műsor9', 2, 'Szereplő8'),
('Műsor10', 1, 'Szereplő9'),
('Műsor10', 2, 'Szereplő10');

INSERT INTO Projection (show_name, episode_number, channel_name, air_datetime) VALUES
('Műsor1', 2, 'Csatorna2', '2023-11-28 02:00:00'),
('Műsor2', 1, 'Csatorna3', '2023-11-29 03:00:00'),
('Műsor2', 2, 'Csatorna4', '2023-11-30 04:00:00'),
('Műsor3', 1, 'Csatorna5', '2023-12-01 05:00:00'),
('Műsor3', 2, 'Csatorna6', '2023-12-02 06:00:00'),
('Műsor4', 1, 'Csatorna7', '2023-11-27 07:00:00'),
('Műsor4', 2, 'Csatorna8', '2023-11-28 08:00:00'),
('Műsor5', 1, 'Csatorna9', '2023-11-29 09:00:00'),
('Műsor5', 2, 'Csatorna10', '2023-11-30 10:00:00'),
('Műsor6', 2, 'Csatorna2', '2023-12-02 12:00:00'),
('Műsor7', 1, 'Csatorna3', '2023-11-27 13:00:00'),
('Műsor7', 2, 'Csatorna4', '2023-11-28 14:00:00'),
('Műsor8', 1, 'Csatorna5', '2023-11-29 15:00:00'),
('Műsor8', 2, 'Csatorna6', '2023-11-30 16:00:00'),
('Műsor9', 1, 'Csatorna7', '2023-12-01 17:00:00'),
('Műsor9', 2, 'Csatorna8', '2023-12-02 18:00:00'),
('Műsor10', 1, 'Csatorna9', '2023-11-27 19:00:00'),
('Műsor10', 2, 'Csatorna10', '2023-11-28 20:00:00'),
('Műsor1', 2, 'Csatorna2', '2023-11-30 01:00:00'),
('Műsor2', 1, 'Csatorna3', '2023-12-01 02:00:00'),
('Műsor2', 2, 'Csatorna4', '2023-12-02 03:00:00'),
('Műsor3', 1, 'Csatorna5', '2023-11-27 04:00:00'),
('Műsor3', 2, 'Csatorna6', '2023-11-28 05:00:00'),
('Műsor4', 1, 'Csatorna7', '2023-11-29 06:00:00'),
('Műsor4', 2, 'Csatorna8', '2023-11-30 07:00:00'),
('Műsor5', 1, 'Csatorna9', '2023-12-01 08:00:00'),
('Műsor5', 2, 'Csatorna10', '2023-12-02 09:00:00'),
('Műsor6', 2, 'Csatorna2', '2023-11-28 11:00:00'),
('Műsor7', 1, 'Csatorna3', '2023-11-29 12:00:00'),
('Műsor7', 2, 'Csatorna4', '2023-11-30 13:00:00'),
('Műsor8', 1, 'Csatorna5', '2023-12-01 14:00:00'),
('Műsor8', 2, 'Csatorna6', '2023-12-02 15:00:00'),
('Műsor9', 1, 'Csatorna7', '2023-11-27 16:00:00'),
('Műsor9', 2, 'Csatorna8', '2023-11-28 17:00:00'),
('Műsor10', 1, 'Csatorna9', '2023-11-29 18:00:00'),
('Műsor10', 2, 'Csatorna10', '2023-11-30 19:00:00'),
('Műsor1', 1, 'Csatorna1', '2023-11-27 01:00:00'),
('Műsor1', 2, 'Csatorna1', '2023-11-27 02:00:00'),
('Műsor2', 1, 'Csatorna1', '2023-11-27 03:00:00'),
('Műsor2', 2, 'Csatorna1', '2023-11-27 04:00:00'),
('Műsor3', 1, 'Csatorna1', '2023-11-27 05:00:00'),
('Műsor3', 2, 'Csatorna1', '2023-11-27 06:00:00'),
('Műsor4', 1, 'Csatorna1', '2023-11-27 07:00:00'),
('Műsor4', 2, 'Csatorna1', '2023-11-27 08:00:00'),
('Műsor5', 1, 'Csatorna1', '2023-11-27 09:00:00'),
('Műsor5', 2, 'Csatorna1', '2023-11-27 10:00:00'),
('Műsor6', 1, 'Csatorna1', '2023-11-27 11:00:00'),
('Műsor6', 2, 'Csatorna1', '2023-11-27 12:00:00'),
('Műsor7', 1, 'Csatorna1', '2023-11-27 13:00:00'),
('Műsor7', 2, 'Csatorna1', '2023-11-27 14:00:00'),
('Műsor8', 1, 'Csatorna1', '2023-11-27 15:00:00'),
('Műsor8', 2, 'Csatorna1', '2023-11-27 16:00:00'),
('Műsor9', 1, 'Csatorna1', '2023-11-27 17:00:00'),
('Műsor9', 2, 'Csatorna1', '2023-11-27 18:00:00'),
('Műsor10', 1, 'Csatorna1', '2023-11-27 19:00:00'),
('Műsor10', 2, 'Csatorna1', '2023-11-27 20:00:00');