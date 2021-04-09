<?php

namespace Novokhatsky\Db;

Class Db
{
    public $errInfo = [];

    private $db;

    public function __construct($config)
    {

        $dsn = 'mysql:host=' . $config::SRV . ';dbname=' . $config::DB . ';charset=utf8';
        $this->db = new \PDO($dsn, $config::USER, $config::PASS);

        return $this;
    }

    function getList($query, $params = [])
    {
        $stmt = $this
                    ->db
                    ->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    function getRow($query, $params = [])
    {
        $stmt = $this
                    ->db
                    ->prepare($query);
        $stmt->execute($params);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    function getValue($query, $params = [])
    {
        $stmt = $this
                    ->db
                    ->prepare($query);
        $stmt->execute($params);

        $result = $stmt->fetch(\PDO::FETCH_NUM);

        if ($result) {
            return $result[0];
        }

        return false;
    }

    function insertData($query, $params = [])
    {
        $stmt = $this
                    ->db
                    ->prepare($query);

        if ($stmt->execute($params)) {

            return $this
                        ->db
                        ->lastInsertId();
        } else {
            $this->errInfo = $stmt->errorInfo();

            return -1;
        }
    }

    function updateData($query, $params = [])
    {
        $stmt = $this
                    ->db
                    ->prepare($query);

        if ($stmt->execute($params)) {

            return $stmt->rowCount();
        } else {
            $this->errInfo = $stmt->errorInfo();

            return -1;
        }
    }

    function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    function rollBack()
    {
        $this->db->rollBack();
    }

    function commit()
    {
        $this->db->commit();
    }
}
