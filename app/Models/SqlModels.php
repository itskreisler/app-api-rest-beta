<?php

namespace App\Models;

use Core\DataBase;

class SqlModels
{
    private static $db;

    /*
     * Devuelve filas de la base de datos según las condiciones
     * @table nombre de la tabla
     * @conditions array select, where, order_by, limit and return_type conditions
     */
    public static function getRows($table, $conditions = array())
    {
        self::$db = new DataBase();
        $sql = 'SELECT ';
        $sql .= array_key_exists("select", $conditions) ? $conditions['select'] : '*';
        $sql .= ' FROM ' . $table;
        if (array_key_exists("where", $conditions)) {
            $sql .= ' WHERE ';
            $i = 0;
            foreach ($conditions['where'] as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $sql .= $pre . $key . " = '" . $value . "'";
                $i++;
            }
        }
        if (array_key_exists("order_by", $conditions)) {
            $sql .= ' ORDER BY ' . $conditions['order_by'];
        }
        if (array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
            $sql .= ' LIMIT ' . $conditions['start'] . ',' . $conditions['limit'];
        } elseif (!array_key_exists("start", $conditions) && array_key_exists("limit", $conditions)) {
            $sql .= ' LIMIT ' . $conditions['limit'];
        }
        if (array_key_exists("return_type", $conditions) && $conditions['return_type'] != 'all') {
            switch ($conditions['return_type']) {
                case 'count':
                    $data = self::$db->rowCount($sql);
                    break;
                case 'single':
                    $data = self::$db->row($sql);
                    break;
                default:
                    $data = '';
            }
        } else {
            $data = self::$db->query($sql);
        }
        return !empty($data) ? $data : false;
    }

    /*
     * Insertar datos en la base de datos
     * @table nombre de la tabla
     * @data array los datos para insertar en la tabla
     */
    public function insert($table, $data)
    {
        self::$db = new DataBase();
        if (!empty($data) && is_array($data)) {
            $columns = '';
            $values = '';
            $i = 0;
            /*if (!array_key_exists('created', $data)) {
                $data['created'] = date("Y-m-d H:i:s");
            }
            if (!array_key_exists('modified', $data)) {
                $data['modified'] = date("Y-m-d H:i:s");
            }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $columns .= $pre . $key;
                $values .= $pre . ":" . $key . "";
                $i++;
            }
            $query = "INSERT INTO " . $table . " (" . $columns . ") VALUES (" . $values . ")";
            $insert = self::$db->query($query, $data);
            return $insert ? true /*self::$db->lastInsertId()*/ : false;
        } else {
            return false;
        }
    }

    /*
     * Actualizar datos en la base de datos
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table, $data, $conditions)
    {
        if (!empty($data) && is_array($data)) {
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            /*if (!array_key_exists('modified', $data)) {
                $data['modified'] = date("Y-m-d H:i:s");
            }*/
            foreach ($data as $key => $val) {
                $pre = ($i > 0) ? ', ' : '';
                $colvalSet .= $pre . $key . "=:" . $key . "";
                $i++;
            }
            if (!empty($conditions) && is_array($conditions)) {
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach ($conditions as $key => $value) {
                    $pre = ($i > 0) ? ' AND ' : '';
                    $whereSql .= $pre . $key . " = '" . $value . "'";
                    $i++;
                }
            }
            $query = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
            $update = self::$db->query($query, $data);
            return $update ? true : false;
        } else {
            return false;
        }
    }

    /*
     * Eliminar datos de la base de datos
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table, $conditions)
    {
        self::$db = new DataBase();
        $whereSql = '';
        if (!empty($conditions) && is_array($conditions)) {
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach ($conditions as $key => $value) {
                $pre = ($i > 0) ? ' AND ' : '';
                $whereSql .= $pre . $key . "=:" . $key . "";
                $i++;
            }
        }
        $query = "DELETE FROM " . $table . $whereSql;
        $delete = self::$db->query($query,$conditions);
        return $delete ? true : false;
    }
}