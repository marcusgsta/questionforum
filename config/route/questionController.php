<?php
/**
 * Routes for user controller.
 */
return [
    "mount" => "question",
    "routes" => [
        [
            "info" => "Question Controller get all questions.",
            "requestMethod" => "get",
            "path" => "show-all",
            "callable" => ["questionController", "getShowAllQuestions"],
        ],
        [
            "info" => "Question Controller show one question.",
            "requestMethod" => "get|post",
            "path" => "show/{id:digit}",
            "callable" => ["questionController", "getShowQuestion"],
        ],
        [
            "info" => "Question Controller show one question.",
            "requestMethod" => "get|post",
            "path" => "show/{id:digit}/{sorted:alpha}",
            "callable" => ["questionController", "getShowQuestion"],
        ],
        [
            "info" => "Create a Question.",
            "requestMethod" => "get|post",
            "path" => "create",
            "callable" => ["questionController", "postCreateQuestion"],
        ],
        [
            "info" => "Accept Answer.",
            "requestMethod" => "get|post",
            "path" => "accept/{id:digit}",
            "callable" => ["answerController", "acceptAnswer"],
        ],
        [
            "info" => "Vote for Question.",
            "requestMethod" => "get|post",
            "path" => "vote/{id:digit}/{score:digit}",
            "callable" => ["questionController", "vote"],
        ],
    ]
];
