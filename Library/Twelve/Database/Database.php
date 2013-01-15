<?php
namespace Twelve\Database;
use
    PDO,
    PDOException;
use \Twelve\Utils\Utils;
class Database extends PDO
{
    public
        $options =
            [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => true,
            ],
        $settings =
            [
                'dsn' => 'mysql:dbname=Test;host=127.0.0.1',
                'username' => 'root',
                'password' => ''
            ];
    public function __construct($settings, $options = [])
    {
        if (!extension_loaded('pdo'))
            throw new PDOException(__CLASS__ . ': The PDO extension is required for this class');

        $this->options = array_merge($this->options, $options);
        try {
            parent::__construct($settings['dsn'], $settings['username'], $settings['password'], $options);
        } catch (PDOException $e) {
            $e->getMessage();
        }
    }
    public function select($tableName, $fields = '*', $where = '', $bind = '')
    {
        $this->bind[] = $bind;
        $this->sql = "SELECT " . $fields . " FROM " . $tableName;
        if(!empty($where))
            $this->sql .= " WHERE " . $where;
            $this->sql .= ";";

        return $this->runQuery($this->sql, $bind);
    }
    public function delete($tableName, $fieldName, $bind = [])
    {
        $this->sql = "DELETE FROM " . $tableName . " WHERE " . $fieldName . ";";
        $this->runQuery($this->sql, $bind);
    }
    public function insert($tableName, $columnValue)
    {
        $this->fields = $this->getColumns($tableName, $columnValue);
        $this->sql = "INSERT INTO " . $tableName . " (" . implode($this->fields, ", ") . ") VALUES (:" . implode($this->fields, ", :") . ");";
        foreach($this->fields as $this->field)
            $this->bind[":$this->field"] = $columnValue[$this->field];
        $this->runQuery($this->sql, $this->bind);
    }
    public function runQuery($sql, $bind = [])
    {
        $this->bind = (array) $bind;
        $this->sql = trim($sql);
        try {
            $this->sth = $this->prepare($this->sql);
            if ($this->sth->execute($this->bind) !== false) {
                Utils::var_dump($this->sth->errorInfo());
                if(preg_match("/^(" . implode("|", ['SELECT', 'DESCRIBE', 'PRAGMA']) . ") /i", $this->sql))

                    return $this->sth->fetchAll(PDO::FETCH_ASSOC);
                if(preg_match("/^(" . implode("|", ['DELETE', 'INSERT', 'UPDATE']) . ") /i", $this->sql))

                    return $this->sth->rowCount();
            } else
                print_r($this->sth);
                print_r($this->sth->errorInfo());

        } catch (PDOException $e) {
            echo $e->getMessage();

            return false;
        }
    }
    public function beginTransaction()
    {
        $this->beginTransaction();
    }
    public function commitTransaction()
    {
        $this->commit();
    }
    public function rollbackTransaction()
    {
        $this->rollBack();
    }

    public function getColumns($tableName, $columnValue = [])
    {
        $this->columnValue = $columnValue;
        $this->driver = $this->getAttribute(PDO::ATTR_DRIVER_NAME);
        if ($this->driver == 'sqlite') {
            $this->sql = "PRAGMA table_info('" . $tableName . "');";
            $this->key = "name";
        } elseif ($this->driver == 'mysql') {
            $this->sql = "DESCRIBE " . $tableName . ";";
            $this->key = "Field";
        } else {
            $this->sql = "SELECT* column_name FROM information_schema.columns WHERE table_name = '" . $tableName . "';";
            $this->key = "column_name";
        }
        if(false !== ($this->list = $this->runQuery($this->sql)))
            foreach($this->list as $this->record)
                $this->fields[] = $this->record[$this->key];

            $return = array_values(array_intersect($this->fields, array_keys($this->columnValue)));

            return $return;
    }
}
