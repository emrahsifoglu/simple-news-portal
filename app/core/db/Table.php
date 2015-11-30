<?php

namespace app\core\db;

abstract class Table  {

    /**
     * @var int
     */
    public $Id = 0;

    /**
     * @var string
     */
    protected $table_name = '';

    /**
     * @var int
     */
    protected $errno = 0;

    /**
     * @var int
     */
    protected $insert_id = 0;

    /**
     * @var int
     */
    protected $affected_rows = 0;

    /**
     * @var int
     */
    protected $fetchMode = 0;

    /**
     * @var array
     */
    protected $result = array();

    /**
     * @var array
     */
    protected $db_conn_params = array();

    /**
     * @var Driver
     */
    protected $driver;

    /**
     * @access protected
     * @param string $table_name
     * @param array $fields
     * @param array $where
     * @param string $order_by
     * @return void
     */
    public function select_statement($table_name, $fields = array('*'), $where = array(), $order_by = ''){
        $database = new $this->driver($this->db_conn_params);
        if ($database->select($table_name, $fields, $where, $order_by)){
            $database->set_fetchMode($this->fetchMode);
            $this->result = $database->result();
        } else {
            $this->errno = $database->errno();
        }

    }

    /**
     * @access protected
     * @param string $table_name
     * @param array $fields_values
     * @return void
     */
    public function insert_statement($table_name, $fields_values = array()) {
        $database = new $this->driver($this->db_conn_params);
        if ($database->insert($table_name, $fields_values)){
            $this->insert_id = $database->insert_id();
            $this->affected_rows = $database->affected_rows();
        } else {
            $this->errno = $database->errno();
        }
    }

    /**
     * @access protected
     * @param string $table_name
     * @param array $fields_values
     * @param array $where
     * @return void
     */
    public function update_statement($table_name, $fields_values = array(), $where = array()){
        $database = new $this->driver($this->db_conn_params);
        if ($database->update($table_name, $fields_values, $where)){
            $this->affected_rows = $database->affected_rows();
        } else {
            $this->errno = $database->errno();
        }
    }

    /**
     * @access protected
     * @param string $table_name
     * @param array $where
     * @return void
     */
    public function delete_statement($table_name, $where = array()){
        $database = new $this->driver($this->db_conn_params);
        if ($database->delete($table_name, $where)){
            $this->affected_rows = $database->affected_rows();
        } else {
            $this->errno = $database->errno();
        }
    }

    public function get_affected_rows(){
        return $this->affected_rows;
    }
}