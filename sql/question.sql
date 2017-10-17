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
)
