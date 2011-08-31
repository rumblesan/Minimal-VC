<?php

abstract class Model
{
    protected $pk_name;
    protected $table_name;
    protected $dbI;
    public    $data       = array();
    protected $data_types = array();

    function __construct($dbI, $pk_name, $table_name)
    {
        $this->dbI         = $dbI;
        $this->pk_name     = $pk_name;
        $this->table_name  = $table_name;
    }

    function get($key)
    {
        return $this->data[$key];
    }

    function set($key, $val)
    {
        if (isset($this->data[$key]))
        {
            $this->data[$key] = $val;
        }
        return $this;
    }

    function __get($key)
    {
        return $this->get($key);
    }

    function __set($key, $val)
    {
        return $this->set($key,$val);
    }

    function create()
    {
        $pk_name = $this->pk_name;
        $keys        = array();
        $values      = array();
        foreach ($this->data as $key => $value)
        {
            if ($key != $pk_name || $value)
            {
                $keys[]   = '`'.$key.'`';
                $values[] = '?';
            }
        }
        $sql  = 'INSERT INTO `' . $this->tablename . '`';
        $sql .= '(' . implode(',',$keys) . ') ';
        $sql .= 'VALUES (' . implode(',',$values) . ')';
        $stmt = $this->dbI->prepare($sql);
        
        $types       = array();
        $values      = array();
        foreach ($this->data as $key => $value)
        {
            if ($key != $pk_name || $value)
            {
                $types[]  = $this->data_types[$key];
                $values[] = &$this->data[$key];
            }
        }
        call_user_func_array(array($stmt, 'bind_param'), array_merge($types, $values));
        $stmt->execute();
        if ( ! $stmt->affected_rows)
        {
            return false;
        }
        $this->set($pk_name,$stmt->insert_id);
        return $this;
    }

    function retrieve($pk_value)
    {
        $pk_name = $this->pk_name;
        $sql  = 'SELECT * FROM `' . $this->tablename . '` ';
        $sql .= 'WHERE `' . $pk_name . '`=?';

        $stmt = $this->dbI->prepare($sql);
        $stmt->bind_param($this->data_types[$pk_name], $pk_value);
        $stmt->execute();

        if ($result = $stmt->get_result())
        {
            $row = $result->fetch_assoc();
            foreach ($row as $key => $value)
            {
                $this->$key = $value;
            }
        }
        return $this;
    }

    function update()
    {
        $pk_name = $this->pk_name;
        $keys        = array();
        $values      = array();
        foreach ($this->data as $key => $value)
        {
            $sets[] = '`'.$key.'` = ?';
        }
        $sql  = 'UPDATE `' . $this->tablename . '` ';
        $sql .= 'SET ' . implode(',',$keys) . ' ';
        $sql .= 'WHERE `' . $pk_name . ' = ?';
        $stmt = $this->dbI->prepare($sql);
        
        $types       = array();
        $values      = array();
        foreach ($this->data as $key => $value)
        {
            if ($key != $pk_name || $value)
            {
                $types[]  = $this->data_types[$key];
                $values[] = &$this->data[$key];
            }
        }
        call_user_func_array(array($stmt, 'bind_param'), array_merge($types, $values));
        return $stmt->execute();
    }


    function delete()
    {
        $pk_name  = $this->pk_name;
        $pk_value = $this->data[$pk_name];
        $sql  = 'DELETE FROM `' . $this->tablename . '` ';
        $sql .= 'WHERE `' . $pk_name . '`=?';
        $stmt = $this->dbI->prepare($sql);
        $stmt->bind_param($this->data_types[$pk_name], $pk_value);
        return $stmt->execute();
    }

    function exists($checkdb=false)
    {
        if ((int)$this->data[$this->pk_name] < 1)
        {
            return false;
        }
        if (!$checkdb)
        {
            return true;
        }
        $sql = 'SELECT 1 FROM `' . $this->tablename . '`';
        $sql = 'WHERE `' . $this->pkname . "` = '" . $this->data[$this->pkname] . "'";
        $result = $this->dbI->query($sql)->fetchAll();
        return count($result);
    }

    function merge($arr)
    {
        if (!is_array($arr))
        {
            return $this;
        }
        foreach ($arr as $key => $value)
        {
            $this->$key = $value;
        }
        return $this;
    }

    function retrieve_one($keys,$values)
    {
        $values = is_scalar($values) ? array($values) : $values;
        $keys   = is_scalar($keys)   ? array($keys)   : $keys;

        if (count($keys) !== count($calues))
        {
            return False;
        }

        $sql = 'SELECT * FROM `' . $this->tablename . '`';

        $sets  = array();
        $types = array();
        $vals  = array();

        foreach ($keys as $key)
        {
            $sets[] = '`'.$key.'` = ?';
        }

        $sql .= ' WHERE ' . implode(' AND ', $keys);
        $sql .= ' LIMIT 1';

        $stmt = $this->dbI->prepare($sql);

        foreach ($keys as $arrkey => $key)
        {
            $types[] = $this->data_types[$key];
            $vals[]  = &$this->values[$arrkey];
        }

        call_user_func_array(array($stmt, 'bind_param'), array_merge($types, $values));
        $stmt->execute();

        if ( ! $result = $stmt->get_result())
        {
            return False;
        }
        else
        {
            $row = $result->fetch_assoc();
            foreach ($row as $key => $value)
            {
                $this->$key = $value;
            }
        }
        return $this;
    }

    function retrieve_many($keys='',$values='')
    {
        $values = is_scalar($values) ? array($values) : $values;
        $keys   = is_scalar($keys)   ? array($keys)   : $keys;

        if (count($keys) !== count($calues))
        {
            return False;
        }

        $sql = 'SELECT * FROM `' . $this->tablename . '`';

        $sets  = array();
        $types = array();
        $vals  = array();

        if (count($keys) === 0)
        {
            foreach ($keys as $key)
            {
                $sets[] = '`'.$key.'` = ?';
            }

            $sql .= ' WHERE ' . implode(' AND ', $keys);
        }

        $stmt = $this->dbI->prepare($sql);

        foreach ($keys as $arrkey => $key)
        {
            $types[] = $this->data_types[$key];
            $vals[]  = &$this->values[$arrkey];
        }

        call_user_func_array(array($stmt, 'bind_param'), array_merge($types, $vals));

        $stmt->execute();

        $object_list = array();
        $class       = get_class($this);

        if ( ! $result = $stmt->get_result())
        {
            return $object_list;
        }
        else
        {
            while ($row = $result->fetch_assoc())
            {
                $thisclass = new $class();
                foreach ($row as $key => $value)
                {
                    $thisclass->$key = $value;
                }
                $object_list[] = $thisclass;
            }
        }
        return $object_list;
    }
}

