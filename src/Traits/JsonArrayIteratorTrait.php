<?php

namespace YusamHub\JsonExt\Traits;

trait JsonArrayIteratorTrait
{
    /**
     * @var string
     */
    private string $_rowClass;

    private int $iteratorPosition;

    private function init(string $rowClass): void
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
}