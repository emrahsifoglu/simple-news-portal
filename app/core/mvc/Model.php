<?php

namespace app\mvc;

use app\core\db\Table;

class Model extends Table {

    protected $model_name;

    /**
     * @param string $model_name
     * @param string $table_name
     * @return \app\mvc\Model
     */
    public function __construct($model_name, $table_name){
        $this->model_name = $model_name;
        $this->table_name = $table_name;
        $this->driver = 'lib\\'.DB_DRIVER;
        $this->db_conn_params = unserialize(DB_CONN_PARAMS);
    }

    /**
     * @access protected
     * @param array $fields
     * @param array $where
     * @return array
     */
    protected function fetch($fields = array(), $where = array()){
        $this->select_statement($this->table_name, $fields, $where);
        return $this->result;
    }

    /**
     * @access public
     * @param array $fields
     * @return array
     */
    public function findAll($fields = array('*')){
        return $this->fetch($fields);
    }

    /**
     * @access public
     * @param array $fields
     * @param array $conditions
     * @return array
     */
    public function find($fields = array('*'), $conditions = array()){
        return $this->fetch($fields, $conditions);
    }


    /**
     * @access private
     * @param array $fields_values
     * @return int
     */
    private function create($fields_values = array()){
        $this->insert_statement($this->table_name, $fields_values);
        return $this->insert_id;
    }

    /**
     * @access public
     * @param array $fields
     * @return array
     */
    public function read($fields = array('*')){
        return $this->fetch($fields, array('id' => $this->Id));
    }

    /**
     * @access public
     * @param array $fields_values
     * @return int
     */
    public function save($fields_values = array()){
        if ($this->Id == 0){
            return $this->create($fields_values);
        } else if ($this->Id > 0){
            $affected_rows = $this->update($fields_values);
            return ($affected_rows == 1) ? $this->Id : 0;
        }
    }

    /**
     * @access public
     * @param string $table_name;
     * @param array $columns
     * @param array $where
     * @param array $joins
     * @return array
     */
    public function select($table_name, $columns = array(), $where = array(), $joins = array()){
        $this->select_statement($table_name, $columns, $where, $joins);
        return $this->result;
    }

    /**
     * @access public
     * @param array $fields_values
     * @return int
     */
    private function update($fields_values = array()){
        $this->update_statement($this->table_name, $fields_values, array('id' => $this->Id));
        return $this->affected_rows;
    }

    /**
     * @access public
     * @param array $conditions
     * @return int
     */
    public function delete($conditions = array()){
        $where = (!empty($conditions)) ? $conditions : array('id' => $this->Id);
        $this->delete_statement($this->table_name, $where);
        return $this->affected_rows;
    }
}