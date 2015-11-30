<?php

namespace lib;

class Bootstrap {

    private $currentController;
    private $currentAction;
    private $url;

    /**
     * @access private
     * @param string $controller
     * @return void
     */
    public function setController($controller){
        if (!empty($controller)) {
            if(file_exists(CONTROLLER_PATH.$controller.'Controller.php')) {
                $this->currentController = $controller.'Controller';
            }
        }
    }

    /**
     * @desc Load controller class file.
     *
     * @access private
     * @return void
     */
    public function loadControllerFile(){
        require_once CONTROLLER_PATH.$this->currentController.'.php';
    }

    /**
     * @desc Create a new instance of controller class and run its  __construct method.
     *
     * @access private
     * @return void
     */
    public function initControllerClass(){
        if (class_exists($this->currentController)){
            $this->currentController = new $this->currentController();
        }
    }

    /**
     * @desc This method checks if the action exists then set $currentAction.
     *
     * @access private
     * @param string $action
     * @param array $params
     * @return void
     */
    public function runControllerAction($action = "index", $params = []){
        if (!empty($action) && method_exists($this->currentController, $action.'Action')){
            $this->currentAction = $action.'Action';
        }
        call_user_func_array([$this->currentController, $this->currentAction], $params);
    }

    /**
     * @desc First checking if url is given then parsing.
     *
     * @access private
     * @return array
     */
    public function parseUrl(){
        $this->url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : [];
    }

    /**
     * @access private
     * @return string
     */
    public function getRoute(){
        return $this->unsetArray(0);
    }

    /**
     * @access private
     * @return string
     */
    public function getAction(){
        return $this->unsetArray(1);
    }

    /**
     * @desc This is a helper method which getRoute and getAction use.
     *
     * @access private
     * @param $index
     * @return string
     */
    public function unsetArray($index){
        if (sizeof($this->url) >= $index){
            if (!empty($this->url[$index])){
                $return = $this->url[$index];
                unset($this->url[$index]);
                return $return;
            }
        }
    }

    /**
     * @access private
     * @return array
     */
    public function getParams(){
        return $this->url ? array_values($this->url) : [];
    }

    /**
     * @return string;
     */
    public function getUrl(){
        return $this->url;
    }

    public function setCurrentController($currentController){
        $this->currentController = $currentController;
    }

    public function setCurrentAction($currentAction){
        $this->currentAction = $currentAction;
    }

} 