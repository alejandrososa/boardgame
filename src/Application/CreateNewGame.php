<?php

namespace Mayordomo\Application;

class CreateNewGame
{
    /**
     * @var string
     */
    private $namePlayerOne;
    /**
     * @var string
     */
    private $namePlayerTwo;
    /**
     * @var int|null
     */
    private $boardSize;

    public function __construct(string $namePlayerOne, string $namePlayerTwo, ?int $boardSize = 0)
    {
        $this->namePlayerOne = $namePlayerOne;
        $this->namePlayerTwo = $namePlayerTwo;
        $this->boardSize = $boardSize;
    }

    public function getNamePlayerOne(): string
    {
        return $this->namePlayerOne;
    }

    public function getNamePlayerTwo(): string
    {
        return $this->namePlayerTwo;
    }

    public function getBoardSize(): ?int
    {
        return $this->boardSize;
    }
}