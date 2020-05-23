<?php

namespace App;

use Aura\SqlQuery\QueryFactory;
use PDO;

class QueryBuilder
{
    private $pdo;
    private $queryFactory;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=app3;charset=utf8", "root", "");
        $this->queryFactory = new QueryFactory('mysql');

    }

    public function getAll($table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    public function insert($data, $table)
    {
        $insert = $this->queryFactory->newInsert();

        $insert
            ->into($table)                   // INTO this table
            ->cols($data);

        $sth = $this->pdo->prepare($insert->getStatement());

        $sth->execute($insert->getBindValues());
    }

    public function update($data, $id, $table)
    {
        $update = $this->queryFactory->newUpdate();

        $update
            ->table($table)                  // update this table
            ->cols($data)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($update->getStatement());

        $sth->execute($update->getBindValues());
    }

    public function delete($id, $table)
    {
        $delete = $this->queryFactory->newDelete();

        $delete
            ->from($table)                   // FROM this table
            ->where('id = :id')           // AND WHERE these conditions
            ->bindValue('id', $id);   // bind one value to a placeholder

        $sth = $this->pdo->prepare($delete->getStatement());

        $sth->execute($delete->getBindValues());
    }

    public function getOne($id, $table)
    {
        $select = $this->queryFactory->newSelect();

        $select->cols(['*'])
            ->from($table)
            ->where('id = :id')
            ->bindValue('id', $id);

        $sth = $this->pdo->prepare($select->getStatement());

        $sth->execute($select->getBindValues());

        return $sth->fetch(PDO::FETCH_ASSOC);
    }
}