DROP TABLE IF EXISTS Question;
CREATE TABLE Question (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "questiontitle" TEXT NOT NULL,
    "questiontext" TEXT NOT NULL,
    "userid" INTEGER NOT NULL,
    "tag" TEXT,
    "votesum" INTEGER,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME,
    FOREIGN KEY (userid) REFERENCES User(id)
);

DROP TABLE IF EXISTS Answer;
CREATE TABLE Answer (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "answertitle" TEXT NOT NULL,
    "answertext" TEXT NOT NULL,
    "userid" INTEGER NOT NULL,
    "questionid" INTEGER NOT NULL,
    "votesum" INTEGER,
    "accepted" INTEGER,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME,
    FOREIGN KEY (userid) REFERENCES User(id),
    FOREIGN KEY (questionid) REFERENCES Question(id)
);

DROP TABLE IF EXISTS Comment;
CREATE TABLE Comment (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "commenttext" TEXT NOT NULL,
    "questionid" INTEGER,
    "answerid" INTEGER,
    "votesum" INTEGER DEFAULT 0,
    "userid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME,
    FOREIGN KEY (userid) REFERENCES User(id)
);
/* skipping foreign key on questionid and answerid for now
    structure needs to be rebuilt to make them foreign keys,
    since one of them always will be empty */

DROP TABLE IF EXISTS Tagquestion;
CREATE TABLE Tagquestion (
    "tagid" INTEGER NOT NULL,
    "questionid" INTEGER NOT NULL,
    FOREIGN KEY (tagid) REFERENCES Tag(id),
    FOREIGN KEY (questionid) REFERENCES Question(id)
);


DROP TABLE IF EXISTS Tag;
CREATE TABLE Tag (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "tagtext" TEXT NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


DROP TABLE IF EXISTS User;
CREATE TABLE User (
    "id" INTEGER PRIMARY KEY NOT NULL,
    "acronym" TEXT UNIQUE NOT NULL,
    "email" TEXT NOT NULL,
    "gravatar" TEXT,
    "password" TEXT,
    "role" INTEGER NOT NULL,
    "created" TIMESTAMP,
    "updated" DATETIME,
    "deleted" DATETIME,
    "active" DATETIME,
    "rank" INTEGER DEFAULT 0
);


DROP TABLE IF EXISTS Voteanswer;
CREATE TABLE Voteanswer (
    "userid" INTEGER NOT NULL,
    "answerid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userid) REFERENCES User(id),
    FOREIGN KEY (answerid) REFERENCES Answer(id)
);


DROP TABLE IF EXISTS Votecomment;
CREATE TABLE Votecomment (
    "userid" INTEGER NOT NULL,
    "commentid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userid) REFERENCES User(id),
    FOREIGN KEY (commentid) REFERENCES Comment(id)
);


DROP TABLE IF EXISTS Votequestion;
CREATE TABLE Votequestion (
    "userid" INTEGER NOT NULL,
    "questionid" INTEGER NOT NULL,
    "created" TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (userid) REFERENCES User(id),
    FOREIGN KEY (questionid) REFERENCES Question(id)
);
