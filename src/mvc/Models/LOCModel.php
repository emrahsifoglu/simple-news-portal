<?php

use app\mvc\Model;

class LOCModel extends Model {

    /**
     * @return \LOCModel
     */
    public function __construct(){
        parent::__construct('LOC', 'list_of_categories');
        $this->Id = 0;
    }

    /**
     * @param int $id
     * @return array
     */
    public function findByNewsId($id){
        return $this->select('list_of_categories loc JOIN categories c ON c.id = loc.category_id', ['c.id, c.title'], ['news_id' => $id]);
    }

} 