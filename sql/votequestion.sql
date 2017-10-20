DROP TABLE IF EXISTS Votequestion;
CREATE TABLE Votequestion (
    "userid" INTEGER NOT NULL,
    "questionid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
