<?php
/**
 * Routes for tag controller.
 */
return [
    "mount" => "tag",
    "routes" => [
        [
            "info" => "Show questions for tag.",
            "requestMethod" => "get|post",
            "path" => "{id:digit}",
            "callable" => ["tagController", "showQuestions"],
        ],
        [
            "info" => "Show all tags.",
            "requestMethod" => "get|post",
            "path" => "show-all",
            "callable" => ["tagController", "getAllTags"],
        ],
    ]
];
