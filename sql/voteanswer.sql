DROP TABLE IF EXISTS Voteanswer;
CREATE TABLE Voteanswer (
    "userid" INTEGER NOT NULL,
    "answerid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
