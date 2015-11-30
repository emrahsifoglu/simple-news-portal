<?php

namespace app\core\db;

class Driver implements IDriver {

    public function  __construct($db_conn_params, $charset = 'utf8') {

    }

    public function open($db_conn_params, $charset = 'utf8')
    {
        // TODO: Implement open() method.
    }

    public function query($query, $multi_query_delimiter = ';')
    {
        // TODO: Implement query() method.
    }

    public function set_fetchMode($type)
    {
        // TODO: Implement set_fetchMode() method.
    }

    public function insert($table_name, $fields_values = array())
    {
        // TODO: Implement insert() method.
    }

    public function select($table_name, $fields = array(), $where = array(), $order_by = '')
    {
        // TODO: Implement select() method.
    }

    public function update($table_name, $fields_values = array(), $where = array())
    {
        // TODO: Implement update() method.
    }

    public function delete($table_name, $where = array())
    {
        // TODO: Implement delete() method.
    }

    public function insert_id()
    {
       return 10;
    }

    public function affected_rows()
    {
        return 0;
    }

    public function errno()
    {
        return 0;
    }

    public function disconnect()
    {
        // TODO: Implement disconnect() method.
    }
}