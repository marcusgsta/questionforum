<?php
/**
 * Routes for user controller.
 */
return [
    "mount" => "answer",
    "routes" => [
        [
            "info" => "Vote for Answer.",
            "requestMethod" => "get|post",
            "path" => "vote/{id:digit}/{score:digit}",
            "callable" => ["answerController", "vote"],
        ],
    ]
];
