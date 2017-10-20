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
    "active" DATETIME
)
