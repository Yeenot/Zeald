<?php

use Illuminate\Support;  // https://laravel.com/docs/5.8/collections - provides the collect methods & collections class
require_once('Traits/BuilderHelpers.php');

class QueryBuilder
{
    use BuilderHelpers;

    private $table = null;

    private $joins = [];

    private $columns = ['*'];

    private $conditions = [];

    public static function from($table)
    {
        $queryBuilder = new self();
        $queryBuilder->setTable($table);
        return $queryBuilder;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function join($table, $id, $joinId, $type = 'INNER JOIN')
    {
        $this->joins[] = [$table, $id, $joinId, $type];
        return $this;
    }

    public function select($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $this->conditions[] = [$column, $value, $operator];
        return $this;
    }

    private function generateJoins()
    {
        $query = '';
        foreach ($this->joins as $join) {
            $query = " {$join[3]} {$this->quote($join[0])} ON ({$this->quote($join[1])} = {$this->quote($join[2])})";
        } 
        return $query;
    }

    private function generateColumns()
    {
        return implode(', ', $this->quote($this->columns));
    }

    private function generateConditions()
    {
        $query = count($this->conditions) > 0 ? 'WHERE ' : '';
        
        foreach($this->conditions as $key => $condition) {
            if ($key !== 0)
                $query .= ' AND ';

            $query .= "{$this->quote($condition[0])} {$condition[2]} '{$condition[1]}'";

        }
        return $query;
    }

    public function get()
    {
        $columns = $this->generateColumns();
        $joins = $this->generateJoins();
        $conditions = $this->generateConditions();
        $sql = "SELECT {$columns} FROM {$this->quote($this->table)}{$joins} {$conditions}";
        // die(dd($sql));
        return collect(query($sql)) ?: [];
    }
}