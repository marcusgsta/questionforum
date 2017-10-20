<?php
/**
 * Routes for comment controller.
 */
return [
    "mount" => "comment",
    "routes" => [
        [
            "info" => "Vote for comment-question.",
            "requestMethod" => "get|post",
            "path" => "vote/{id:digit}/{score:digit}",
            "callable" => ["commentController", "vote"],
        ],
    ]
];
