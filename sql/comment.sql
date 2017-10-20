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
    "active" DATETIME
)
