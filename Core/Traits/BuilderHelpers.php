<?php

// namespace Core\Traits;

Trait BuilderHelpers
{
    protected function quote($values)
    {
        $delimiter = '.';
        if (!is_array($values)) {
            if (strpos($values, $delimiter) !== false)
                return implode($delimiter, $this->quote(explode('.', $values)));
            else
                return ($values !== '*') ?  "`{$values}`" : $values;
        }

        $length = count($values);
        for ($counter = 0; $counter < $length; $counter++) {
            $values[$counter] = $this->quote($values[$counter]);
        }

        return $values;
    }

    public function search($keys, $values)
    {
        $values->each(function($value, $key) use ($keys) {
            if (array_key_exists($key, $keys)) {
                $this->where($keys[$key], $value);
            }
        });
        
        return $this;
    }
}