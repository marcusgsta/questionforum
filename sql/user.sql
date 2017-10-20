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
    "rank" INTEGER DEFAULT 0);
