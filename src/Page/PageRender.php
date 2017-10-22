<?php

namespace Marcusgsta\Page;

use \Anax\DI\InjectionAwareInterface;
use \Anax\DI\InjectionAwareTrait;
use \Anax\TextFilter;
use \Anax\Page\PageRenderInterface;

/**
 * A default page rendering class.
 */
class PageRender implements PageRenderInterface, InjectionAwareInterface
{
    use InjectionAwareTrait;



    /**
     * Render a standard web page using a specific layout.
     *
     * @param array   $data   variables to expose to layout view.
     * @param integer $status code to use when delivering the result.
     * @SuppressWarnings(PHPMD.ExitExpression)
     * @return void
     */
    public function renderPage($data, $status = 200)
    {
        $data["stylesheets"] = ["css/bootstrap.min.css", "css/style.css"];
        $data["javascripts"] = [
            "js/jquery-3.2.1.slim.min.js",
            "js/tether.min.js",
            "js/popper.min.js",
            "js/bootstrap.min.js",
            "js/custom.js"
        ];
        $data["commentableRoutes"] = ["index", "about"];

        // create navbar from class
        $navbar = new \Marcusgsta\Navbar\Navbar();
        $navbar->configure();
        $navbarconfig = $navbar->config;

        // check if a user is logged in
        $acronym = $this->di->get("loginController")->isLoggedIn();

        //$role = $this->di->get("commentController")->getRole($acronym);

        // Add common header, navbar and footer
        // Add layout, render it, add to response and send.
        $view = $this->di->get("view");
        $view->add("take1/header", [], "header");
        $view->add("take1/navbar", $navbarconfig, "navbar");
        // $view->add("take1/questions", ["user" => $acronym], "questionsection");

        //$comments = $this->di->get("commentController")->getComments();
        // $comments = $comments['items'];

        //$commentableRoutes = $data['commentableRoutes'];
        // $view->add("take1/comment", ["user" => $acronym], "commentsection");
        // $view->add(
        //     "take1/comment",
        //     [
        //     "comments" => $comments,
        //     "user" => $acronym,
        //     "role" => $role,
        //     "commentableRoutes" => $commentableRoutes
        //     ],
        //     "commentsection"
        // );

        $view->add("take1/footer", ["user" => $acronym], "footer");
        $view->add("default1/layout", $data, "layout");
        $body = $view->renderBuffered("layout");
        $this->di->get("response")->setBody($body)
                                  ->send($status);
        exit;
    }
}
