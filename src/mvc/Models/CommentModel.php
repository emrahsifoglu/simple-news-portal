<?php

use app\mvc\Model;

class CommentModel extends Model {

    /**
     * @return \CommentModel
     */
    public function __construct(){
        parent::__construct('Comment', 'comments');
        $this->Id = 0;
    }

    /**
     * @access public
     * @param int $news_id
     * @return array
     */
    public function findByNewsId($news_id){
        $table_join = 'comments c JOIN users u ON u.id = c.user_id';
        $fields = ['c.id as id', 'u.username', 'content', 'made_date'];
        $where = ['c.news_id' => $news_id];
        return $this->select($table_join, $fields, $where, 'ORDER BY c.id DESC');
    }

    /**
     * @dec This method will return last comment after it is saved with username
     *
     * @access public
     * @return array
     */
    public function getUsernameAndDate(){
        $table_join = 'comments c JOIN users u ON u.id = c.user_id';
        $fields = ['c.id as id', 'u.username', 'made_date'];
        $where = ['c.id' => $this->Id];
        return $this->select($table_join, $fields, $where)[0];
    }

    /**
     * @dec This method will return all comments with usernames and dates
     *
     * @access public
     * @return array
     */
    public function loadAll(){
        $table_join = 'comments c JOIN users u ON u.id = c.user_id';
        $fields = ['c.id as id', 'u.username', 'content', 'made_date'];
        $where = [];
        return $this->select($table_join, $fields, $where, 'ORDER BY c.id DESC');
    }
} 