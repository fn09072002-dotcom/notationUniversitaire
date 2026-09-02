
drop SCHEMA IF EXISTS notation_universitaire CASCADE;
CREATE SCHEMA notation_universitaire;
drop TABLE IF EXISTS copie_examen CASCADE;
CREATE TABLE copie_examen (
    id SERIAL PRIMARY KEY,
    date_depot DATE NOT NULL,
    date_limite DATE NOT NULL,
    note_brute FLOAT NOT NULL,
    note_finale FLOAT NOT NULL,
    penalite_appliquee BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO copie_examen (date_depot, date_limite, note_brute, note_finale, penalite_appliquee)
VALUES ('2026-06-06', '2026-06-05', 15.5, 13.5, true);

SELECT * FROM copie_examen;
\q