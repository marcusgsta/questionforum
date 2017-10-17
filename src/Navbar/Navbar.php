<?php
/**
*   Navbar class
*
*/
namespace Marcusgsta\Navbar;

/**
*   Navbar class
*   creates html for navbar
*
*/
class Navbar
{

    public $config;

    public function configure()
    {
        $array = require ANAX_INSTALL_PATH . "/config/navbar.php";
        return $this->config = $array;
    }

    /**
    *   get html for navbar
    *   creates html for navbar
    *   @var array $items array of navbar items
    *   @var string $html html for navbar
    *   @return string $html html for navbar
    */
    public function getHtml()
    {
        $items = $this->config;

        $page = basename($_SERVER['REQUEST_URI']);

        // $values = $navbar['items'];
        // $navbarClass = $navbar['config']['navbar-class'];
        $values = $items['items'];
        $navbarClass = $items['config']['navbar-class'];


        $html = "<nav class='$navbarClass' style='background-color: #e3f2fd;'>
        <a class='navbar-brand' href='index'>Anax - ramverk med moduler</a>
        <button class='navbar-toggler navbar-toggler-right' type='button' data-toggle='collapse'
        data-target='#navbarsExample09' aria-controls='navbarsExample09'
         aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
          </button>
          <div class='navbar-collapse collapse' id='navbarsExample09'>";
        $html .= "<ul class='navbar-nav mr-auto mt-2 mt-lg-0'>";
        // $page = basename($_SERVER['REQUEST_URI']);
        //foreach ($values as $key => $value) {
        foreach ($values as $value) {
            $route = $value['route'];
            $text = $value['text'];
            //$url = $app->url->create($route);

            $html .= "<li class=\"";

            if ($value['route'] == "") {
                $value['route'] = "htdocs";
            }

            if ($page == $value['route']) {
                //$html .= "selected";
                $html .= "nav-item active";
            } else {
                $html .= "nav-item";
            }
            // var_dump($_SERVER['REQUEST_URI']);
            // var_dump($route);
            // ($page == $url) ? "selected" : "";
            // $html .= "\"><a class='nav-link' href='" . $url . "'>" . $text . "</a></li>";
            //$html .= "\"><a class='nav-link' href='" . $route . "'>" . $text . "</a></li>";
            $html .= "\"><a class='nav-link' href='" . $route . "'>" . $text . "</a></li>";
        }
        $html .= "</ul></div></nav>";

        return $html;
    }
}
