<?php

abstract class Model
{
    protected $pk_name;
    protected $tablename;
    protected $dbI;
    public    $data       = array();
    protected $data_types = array();

    function __construct($dbI, $pk_name, $tablename)
    {
        $this->dbI        = $dbI;
        $this->pk_name    = $pk_name;
        $this->tablename  = $tablename;
    }

    protected function pdo_type($key)
    {
        switch ($this->data_types[$key])
        {
            case 's':
                return PDO::PARAM_STR;
            case 'i':
                return PDO::PARAM_INT;
            default:
                return False;
        }
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
                $keys[]   = '`' . $key . '`';
                $values[] = ':' . $key . '';
            }
        }
        $sql  = 'INSERT INTO `' . $this->tablename . '`';
        $sql .= '(' . implode(', ',$keys) . ') ';
        $sql .= 'VALUES (' . implode(', ',$values) . ')';
        $stmt = $this->dbI->prepare($sql);

        foreach ($this->data as $key => $value)
        {
            if ($key != $pk_name || $value)
            {
                $pdo_type = $this->pdo_type($key);
                $value    = $this->data[$key];
                $stmt->bindValue(':' . $key, $value, $pdo_type);
            }
        }

        $stmt->execute();
        if ( ! $stmt->rowCount())
        {
            return false;
        }
        $this->set($pk_name,$this->dbI->lastInsertID());
        return $this;
    }

    function retrieve($pk_value)
    {
        $pk_name = $this->pk_name;
        $sql  = 'SELECT * FROM `' . $this->tablename . '` ';
        $sql .= 'WHERE `' . $pk_name . '` = :' . $pk_name;

        $stmt = $this->dbI->prepare($sql);
        $stmt->bindValue(':' . $pk_name, $pk_value, $this->pdo_type($pk_name));
        $stmt->execute();

        if ($results = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            foreach ($results as $key => $value)
            {
                $this->$key = $value;
            }
        }
        return $this;
    }

    function update()
    {
        $pk_name = $this->pk_name;
        $sets        = array();
        foreach ($this->data as $key => $value)
        {
            if ($key != $pk_name || $value)
            {
                $sets[] = '`'.$key.'` = :' . $key;
            }
        }
        $sql  = 'UPDATE `' . $this->tablename . '` ';
        $sql .= 'SET ' . implode(',',$sets) . ' ';
        $sql .= 'WHERE `' . $pk_name . '` = :' . $pk_name;
        $stmt = $this->dbI->prepare($sql);

        foreach ($this->data as $key => $value)
        {
            if ($key != $pk_name || $value)
            {
                $pdo_type  = $this->pdo_type($key);
                $value     = $this->data[$key];
                $stmt->bindValue(':' . $key, $value, $pdo_type);
            }
        }
        return $stmt->execute();
    }

    function delete()
    {
        $pk_name  = $this->pk_name;
        $pk_value = $this->data[$pk_name];
        $sql  = 'DELETE FROM `' . $this->tablename . '` ';
        $sql .= 'WHERE `' . $pk_name . '` = :' . $pk_name;
        $stmt = $this->dbI->prepare($sql);
        $stmt->bindValue(':' . $pk_name, $pk_value, $this->pdo_type($pk_name));
        return $stmt->execute();
    }

    function exists($checkdb=false)
    {
        $pk_name  = $this->pk_name;
        $pk_value = $this->data[$pk_name];
        if ((int)$pk_value < 1)
        {
            return false;
        }
        if (!$checkdb)
        {
            return true;
        }
        $sql  = 'SELECT 1 FROM `' . $this->tablename . '` ';
        $sql .= 'WHERE `' . $pk_name . "` = :" . $pk_name;
        $stmt = $this->dbI->prepare($sql);
        $stmt->bindValue(':' . $pk_name, $pk_value, $this->pdo_type($pk_name));
        $stmt->execute();
        return count($stmt->fetchAll());
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

        if (count($keys) !== count($values))
        {
            return False;
        }

        $sql = 'SELECT * FROM `' . $this->tablename . '`';

        $sets  = array();

        foreach ($keys as $key)
        {
            $sets[] = '`'.$key.'` = :' . $key;
        }

        $sql .= ' WHERE ' . implode(' AND ', $sets);
        $sql .= ' LIMIT 1';

        $stmt = $this->dbI->prepare($sql);

        for ($i = 0; $i < count($keys); $i++)
        {
            $key       = $keys[$i];
            $value     = $values[$i];
            $pdo_type  = $this->pdo_type($key);
            $stmt->bindValue(':' . $key, $value, $pdo_type);
        }

        $stmt->execute();

        if ( ! $results = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            return False;
        }
        else
        {
            foreach ($results as $key => $value)
            {
                $this->$key = $value;
            }
        }
        return $this;
    }

    function retrieve_many($keys='',$values='')
    {
        $values = $values == '' ? array() : $values;
        $keys   = $keys   == '' ? array() : $keys;

        $values = is_scalar($values) ? array($values) : $values;
        $keys   = is_scalar($keys)   ? array($keys)   : $keys;

        if (count($keys) !== count($values))
        {
            return False;
        }

        $sql = 'SELECT * FROM `' . $this->tablename . '`';

        $sets  = array();

        if (count($keys) !== 0)
        {
            foreach ($keys as $key)
            {
                $sets[] = '`'.$key.'` = :' . $key;
            }

            $sql .= ' WHERE ' . implode(' AND ', $sets);
        }

        echo $sql . '<br>';
        $stmt = $this->dbI->prepare($sql);

        for ($i = 0; $i < count($keys); $i++)
        {
            $key       = $keys[$i];
            $value     = $values[$i];
            $pdo_type  = $this->pdo_type($key);
            $stmt->bindValue(':' . $key, $value, $pdo_type);
        }

        $stmt->execute();

        $object_list = array();
        $class       = get_class($this);

        while ( $result = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $thisclass = new $class($this->dbI);
            foreach ($result as $key => $value)
            {
                $thisclass->$key = $value;
            }
            $object_list[] = $thisclass;
        }
        return $object_list;
    }
}
