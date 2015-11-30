<?php

use app\mvc\Model;

class RoleModel extends Model {

    /**
     * @return \RoleModel
     */
    public function __construct(){
        parent::__construct('Role', 'user_role');
    }

} 