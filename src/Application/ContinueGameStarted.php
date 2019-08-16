<?php

namespace Mayordomo\Application;

class ContinueGameStarted
{
    /**
     * @var int
     */
    private $fieldToMark;

    public function __construct(int $fieldToMark)
    {
        $this->fieldToMark = $fieldToMark;
    }

    /**
     * @return int
     */
    public function getFieldToMark(): int
    {
        return $this->fieldToMark;
    }
}