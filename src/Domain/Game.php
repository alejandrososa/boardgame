<?php

namespace Mayordomo\Domain;

use DomainException;
use stdClass;

class Game implements Entity
{
    /**
     * @var Board
     */
    private $board;

    /**
     * @var Player
     */
    private $playerOne;

    /**
     * @var Player
     */
    private $playerTwo;

    /**
     * @var Player
     */
    private $nowPlayerTurn;

    private function __construct(Board $board, Player $playerOne, Player $playerTwo, ?Player $currentPlayer = null)
    {
        $this->board = $board;
        $this->playerOne = $playerOne;
        $this->playerTwo = $playerTwo;
        $this->nowPlayerTurn = $currentPlayer;
    }

    public function getBoard(): Board
    {
        return $this->board;
    }

    public function getNowPlayerTurn(): Player
    {
        return $this->nowPlayerTurn;
    }

    public function getPlayerOne(): Player
    {
        return $this->playerOne;
    }

    public function getPlayerTwo(): Player
    {
        return $this->playerTwo;
    }

    public static function startNewGame(Board $board, Player $playerOne, Player $playerTwo): self
    {
        $self = new self($board, $playerOne, $playerTwo);
        $self->chooseFirstPlayerRandomly();
        return $self;
    }

    private function chooseFirstPlayerRandomly(): void
    {
        $players = [$this->playerOne, $this->playerTwo];
        $selected = array_rand($players, 1);
        $this->nowPlayerTurn = $players[$selected];
    }

    public static function continueFromArray(array $history): self
    {
        if (empty($history['board'])
            || empty($history['player_one'])
            || empty($history['player_two'])
            || empty($history['current_player'])) {
            throw new DomainException('invalid history');
        }

        return new self(
            $history['board'],
            $history['player_one'],
            $history['player_two'],
            $history['current_player']
        );
    }

    public function makeAMove(int $field): void
    {
        $size = $this->board->getSize();
        $panel = $this->board->getPanel();
        $player = $this->getNowPlayerTurn();

        $panel = $this->moveFieldAlong($field, $size, $panel, $player);
        $panel = $this->moveFieldBack($field, $panel, $player);

        $panel[$field] = $player->getPosition();

        $this->board = Board::createFromBoard($panel);
    }

    private function moveFieldAlong(int $field, int $size, array $panel, Player $player): array
    {
        if ($field !== ($size - 1)) {
            $forward = array_slice($panel, $field, -1, true);
            $checker = $this->assignFieldsTo($forward, $panel[$field], $player, false);
            if ($checker->paint) {
                $panel = $this->paintFields($panel, $player, $field + 1, $checker->limit);
            }
        }
        return $panel;
    }

    private function moveFieldBack(int $field, array $panel, Player $player): array
    {
        if ($field !== 0) {
            $backward = array_slice($panel, 0, $field, true);
            $checker = $this->assignFieldsTo($backward, $panel[$field], $player, true);
            if ($checker->paint) {
                $panel = $this->paintFields($panel, $player, $checker->limit, $field);
            }
        }
        return $panel;
    }

    private function paintFields(array $panel, Player $player, $index, $length): array
    {
        for ($i = $index; $i <= $length; $i++) {
            $panel[$i] = $player->getPosition();
        }
        return $panel;
    }

    private function assignFieldsTo($forward, $case, Player $player, $reverse = true): stdClass
    {
        $checker = new stdClass();
        $checker->paint = false;
        $checker->limit = 0;

        if ($reverse) {
            $forward = array_reverse($forward, true);
        }

        foreach ($forward as $position => $cell) {
            if ($cell != $case) {
                if ($cell == $player->getPosition()) {
                    $checker->paint = true;
                    $checker->limit = $position;
                }
                break;
            }
        }
        return $checker;
    }

    public function nextPlayerTurn(): void
    {
        $currentPlayer = $this->getNowPlayerTurn();

        if (!$currentPlayer->equals($this->playerOne)) {
            $this->changePlayer($this->playerOne);
        }

        if (!$currentPlayer->equals($this->playerTwo)) {
            $this->changePlayer($this->playerTwo);
        }
    }

    private function changePlayer(Player $player): void
    {
        $this->nowPlayerTurn = $player;
    }

    public function checkIfPlayerWins(Player $player): bool
    {
        $currentPlayerId = $player->getPosition();

        $panel = $this->board->getPanel();
        $results = array_unique($panel);

        if (count($results) > 1) {
            return false;
        }

        return current($results) === $currentPlayerId;
    }

    public function sameIdentityAs(Entity $other): bool
    {
        /* @var self $other */
        return get_class($this) === get_class($other)
            && $this->board->equals($other->getBoard())
            && $this->playerOne->equals($other->getPlayerOne())
            && $this->playerTwo->equals($other->getPlayerTwo());
    }

    public function toArray(): array
    {
        $winner = 'none';
        if($this->checkIfPlayerWins($this->getPlayerOne())){
            $winner = $this->getPlayerOne()->getName();
        }
        if($this->checkIfPlayerWins($this->getPlayerTwo())){
            $winner = $this->getPlayerTwo()->getName();
        }
        return [
            'board' => $this->getBoard()->toArray(),
            'player_one' => $this->getPlayerOne()->toArray(),
            'player_two' => $this->getPlayerTwo()->toArray(),
            'current_player' => $this->getNowPlayerTurn()->toArray(),
            'player_win' => $winner
        ];
    }
}