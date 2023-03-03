<?php

namespace YusamHub\JsonExt;

use YusamHub\JsonExt\Interfaces\ArrayableInterface;
use YusamHub\JsonExt\Interfaces\JsonArrayInterface;
use YusamHub\JsonExt\Interfaces\JsonObjectInterface;
use YusamHub\JsonExt\Traits\JsonableTrait;
use YusamHub\JsonExt\Traits\CommonTrait;
use YusamHub\JsonExt\Traits\JsonArrayIteratorTrait;

class JsonArray implements JsonArrayInterface, \Iterator
{
    use JsonArrayIteratorTrait;
    use JsonableTrait;
    use CommonTrait;

    /**
     * @var array
     */
    private array $_rows = [];

    /**
     * @param string $rowClass
     */
    public function __construct(string $rowClass)
    {
        $this->init($rowClass);
    }

    /**
     * @param object|array|string|null $source
     * @return object
     */
    public function addRow($source = null): object
    {
        if (is_object($source)) {
            $o = $source;
            if (!($o instanceof JsonObjectInterface)) {
                throw new \RuntimeException("Object is not " . JsonObjectInterface::class);
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
     * @param array|string|null $keyValuePairs
     * @return object|null
     */
    public function findFirst($keyValuePairs): ?object
    {
        if (empty($keyValuePairs)) return null;

        if (!$this->countRows()) return null;

        foreach($this->_rows as $row) {
            if ($row instanceof JsonObjectInterface && $row->isEqual($keyValuePairs)) {
                return $row;
            }
        }

        return null;
    }

    /**
     * @param array|string|null $keyValuePairs
     * @return object|null
     */
    public function findAll($keyValuePairs): object
    {
        $out = new static($this->_rowClass);

        if (empty($keyValuePairs)) return $out;

        if (!$this->countRows()) return $out;

        foreach($this->_rows as $row) {
            if ($row instanceof JsonObjectInterface && $row->isEqual($keyValuePairs)) {
                $out->addRow($row);
            }
        }

        return $out;
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
     * @param array $filterKeys
     * @return void
     */
    public function import($source, array $filterKeys = []): void
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
