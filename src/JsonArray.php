<?php

namespace YusamHub\JsonExt;

use YusamHub\JsonExt\Interfaces\ArrayableInterface;
use YusamHub\JsonExt\Interfaces\JsonArrayInterface;
use YusamHub\JsonExt\Interfaces\JsonObjectInterface;
use YusamHub\JsonExt\Traits\JsonableTrait;

class JsonArray implements JsonArrayInterface, \Iterator
{
    use JsonableTrait;

    /**
     * @var string
     */
    private string $_rowClass;

    /**
     * @var array
     */
    private array $_rows = [];

    private int $iteratorPosition;

    /**
     * @param string $rowClass
     */
    public function __construct(string $rowClass)
    {
        $this->iteratorPosition = 0;
        $this->_rowClass = $rowClass;
    }

    public function current()
    {
        return $this->_rows[$this->iteratorPosition];
    }

    public function next()
    {
        $this->iteratorPosition++;
    }

    public function key()
    {
        return $this->iteratorPosition;
    }

    public function valid()
    {
        return isset($this->_rows[$this->iteratorPosition]);
    }

    public function rewind()
    {
        $this->iteratorPosition = 0;
    }

    /**
     * @param object|array|string|null $source
     * @return object
     * @throws \Exception
     */
    public function addRow($source = null): object
    {
        if (is_object($source)) {
            $o = $source;
            if (!($o instanceof JsonObjectInterface)) {
                throw new \Exception("Object is not " . JsonObjectInterface::class);
            }
        } else {
            $o = $this->newRowObj($source);
        }
        $this->_rows[] = $o;
        return $o;
    }

    /**
     * @param int $index
     * @return object
     */
    public function getRow(int $index): object
    {
        return $this->_rows[$index];
    }

    /**
     * @param int $index
     * @return object
     */
    public function delRow(int $index): object
    {
        $o = $this->_rows[$index];
        unset($this->_rows[$index]);
        $this->_rows = array_values($this->_rows);
        return $o;
    }

    /**
     * @return object|null
     */
    public function firstRow(): ?object
    {
        if (!$this->countRows()) return null;
         return $this->_rows[0];
    }

    /**
     * @param array $attributes
     * @return object|null
     */
    public function findFirstByAttributes(array $attributes): ?object
    {

        if (empty($attributes)) return null;
        if (!$this->countRows()) return null;

        foreach($this->_rows as $row) {
            if ($row instanceof ArrayableInterface) {
                $found = 0;
                foreach($attributes as $k => $v) {
                    $testRow = $row->toArray([$k]);
                    if (!empty($testRow) && isset($testRow[$k])) {
                        if (strpos(strval($testRow[$k]), strval($v)) !== false) {
                            $found++;
                        }
                    }
                }
                if ($found > 0) {
                    return $row;
                }
            }
        }
        return null;
    }

    /**
     * @return int
     */
    public function countRows(): int
    {
        return count($this->_rows);
    }

    /**
     * @return void
     */
    public function clearRows(): void
    {
        $this->_rows = [];
    }

    /**
     * @param array|string|null $source
     * @return object
     */
    private function newRowObj($source): object
    {
        $rowClass = $this->_rowClass;
        $o = new $rowClass();
        if ($o instanceof ArrayableInterface) {
            $o->import($source);
        }
        return $o;
    }

    /**
     * @param array|string|null $source
     * @return void
     */
    public function import($source): void
    {
        if (is_null($source)) return;

        $source = is_array($source) ? $source : (array) json_decode($source, true);

        if (isset($source[0])) {
            $this->clearRows();
            foreach($source as $row) {
                $this->_rows[] = $this->newRowObj($row);
            }
        }
    }

    /**
     * @param array $filterKeys
     * @return array
     */
    public function toArray(array $filterKeys = []): array
    {
        $out = [];
        foreach($this->_rows as $row) {
            if ($row instanceof ArrayableInterface) {
                $out[] = $row->toArray($filterKeys);
            }
        }
        return $out;
    }


}
