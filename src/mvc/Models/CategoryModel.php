<?php

use app\mvc\Model;

class CategoryModel extends Model {

    /**
     * @return \CategoryModel
     */
    public function __construct(){
        parent::__construct('Category', 'categories');
        $this->Id = 0;
    }

} 