DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "questiontitle" TEXT NOT NULL,
    "questiontext" TEXT NOT NULL,
    "userid" INTEGER NOT NULL,
    "tag" TEXT,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tagtext" TEXT NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS Tagquestion;
CREATE TABLE Tagquestion (
    "tagid" INTEGER NOT NULL,
    "questionid" INTEGER NOT NULL
);

DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "answertext" TEXT NOT NULL,
    "userid" INTEGER NOT NULL,
    "questionid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

DROP TABLE IF EXISTS Commentanswer;
CREATE TABLE Commentanswer (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "commenttext" TEXT NOT NULL,
    "answerid" INTEGER NOT NULL,
    "userid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);

DROP TABLE IF EXISTS Commentquestion;
CREATE TABLE Commentquestion (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "commenttext" TEXT NOT NULL,
    "questionid" INTEGER NOT NULL,
    "userid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME
);
