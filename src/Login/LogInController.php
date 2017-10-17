<?php
namespace Anax\Login;

use \Anax\Configure\ConfigureInterface;
use \Anax\Configure\ConfigureTrait;
use \Anax\DI\InjectionAwareInterface;
use \Anax\Di\InjectionAwareTrait;

/**
 * A log in controller class.
 */
class LoginController implements
    ConfigureInterface,
    InjectionAwareInterface
{
    use ConfigureTrait,
        InjectionAwareTrait;



    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function loginUser($acronym)
    {

        $this->di->session->set("user", $acronym);
        //$this->di->session->set("userid", $userid);

        return true;
    }

    /**
     * Check if anyone is logged in.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function anyLoggedin()
    {
        $anyone = $this->di->session->has("user") ? true : false;
        return $anyone;
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function isLoggedIn()
    {
        $user = $this->di->session->get("user");
        return $user;
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return void
     */
    public function logOut()
    {
        // $user = $this->di->session->get("user");
        $this->di->session->delete("user");
        $url = $this->di->get("request")->getServer('HTTP_REFERER');

        $this->di->get("response")->redirect($url);
        return true;
    }
}
