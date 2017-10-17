<?php


return [
    "config" => [
        "navbar-class" => "navbar navbar-expand-lg navbar-light"
        // "navbar-class" => "navbar navbar-toggleable-md navbar-light bd-faded"
    ],
    "items" => [
        "hem" => [
            "text" => "Hem",
            "route" => "",
        ],
        "about" => [
            "text" => "Om",
            "route" => "about",
        ],
        "questions" => [
            "text" => "Frågor",
            "route" => "question/show-all",
        ],
        "tags" => [
            "text" => "Taggar",
            "route" => "tag/show-all",
        ],
        "users" => [
            "text" => "Användare",
            "route" => "user/show-all",
        ],
        "profile" => [
            "text" => "Min profil",
            "route" => "user/profile",
        ],
    ]
];
