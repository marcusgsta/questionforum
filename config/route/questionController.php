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
            "callable" => ["questionController", "getAllQuestions"],
        ],
        [
            "info" => "Question Controller show one question.",
            "requestMethod" => "get|post",
            "path" => "show/{id:digit}",
            "callable" => ["questionController", "getShowQuestion"],
        ],
        [
            "info" => "Create a Question.",
            "requestMethod" => "get|post",
            "path" => "create",
            "callable" => ["questionController", "postCreateQuestion"],
        ],
    ]
];
