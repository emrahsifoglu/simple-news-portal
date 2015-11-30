<?php

namespace app\core;

use lib\Bootstrap;

class App {

    protected $routes = [];

    /**
     * @return \app\core\App
     */
    public function __construct(){

    }

    /**
     * @access public
     * @return void
     */
    public function run(){
        $bootstrap = new Bootstrap();
        $bootstrap->setCurrentController(DEFAULT_CONTROLLER);
        $bootstrap->setCurrentAction(DEFAULT_ACTION);
        $bootstrap->parseUrl();
        $route = $bootstrap->getRoute();

        if (!empty($this->routes[$route]['isOauthRequired']) && !Security::isUserLoggedIn()){
                Helper::redirectTo(WEB.DEFAULT_ROUTE);
        } else {
            if (!empty($this->routes[$route]['controller'])){
                $controller = $this->routes[$route]['controller'];
                $bootstrap->setController($controller);
            }

            $bootstrap->loadControllerFile();
            $bootstrap->initControllerClass();
            $bootstrap->runControllerAction($bootstrap->getAction(), $bootstrap->getParams());
        }
    }

    /**
     * @access public
     * @param string $routeName
     * @param string $controllerName
     * @param bool $isOauthRequired
     * @return void
     */
    public function addRoute($routeName, $controllerName, $isOauthRequired){
        $this->routes[$routeName] = array('controller' => $controllerName, 'isOauthRequired' => $isOauthRequired);
    }

    /**
     * @access public
     * @return array
     */
    public function getRoutes(){
        return $this->routes;
    }
} 