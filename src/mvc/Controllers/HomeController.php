<?php

use app\mvc\Controller;

class HomeController extends Controller  {

    public function __construct(){
        parent::__construct('home');
    }

    public function indexAction(){ 
        $news = $this->loadModel('News')->loadAllWithLimit(4);
        $this->loadView(LAYOUT,'Home/index', 'Home', [STYLES.'grid.css',STYLES.'home.css'], [SCRIPTS.'home.js'], ['news' => $news]);
    }
}
