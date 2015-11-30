<?php

namespace app\core\db;

interface IDriver {

    public function open($db_conn_params, $charset = 'utf8');
    public function query($query, $multi_query_delimiter = ';');
    public function set_fetchMode($type);
    public function insert($table_name, $fields_values = array());
    public function select($table_name, $fields = array(), $where = array(), $order_by = '');
    public function update($table_name, $fields_values = array(), $where = array());
    public function delete($table_name, $where = array());
    public function insert_id();
    public function affected_rows();
    public function errno();
    public function disconnect();

} 