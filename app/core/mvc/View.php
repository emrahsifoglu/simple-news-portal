<?php

namespace app\mvc;

class View  {

    private $route_template;
    private $args = [];

    /**
     * @access public
     * @param string $route_template
     * @param string $title
     * @return \app\mvc\View
     */
    public function __construct($route_template, $title){
        $this->route_template = $route_template;
        $this->args['title'] = $title;
    }

    /**
     * @param array $styles
     * @return void
     */
    public function addStyles($styles){
        $this->args['styles'] = $styles;
    }

    /**
     * @param array $scripts
     * @return void
     */
    public function addScripts($scripts){
        $this->args['scripts'] = $scripts;
    }

    public function addData($data){
        $this->args['data'] = $data;
    }

    public function render($layout) {
        if(file_exists(VIEW_PATH.$this->route_template.'.php')) {
            extract($this->args);
            ob_start();
            require VIEW_PATH.$this->route_template.'.php';
            $content = ob_get_clean();
            require $layout;
        }
    }
} 