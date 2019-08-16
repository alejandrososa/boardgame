<?php

namespace Mayordomo\Domain;

use DomainException;

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
        //TODO MOVE TO HANDLER
        $board->assign20PercentOfFieldsToPlayers();
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

        return new self($history['board'], $history['player_one'], $history['player_two'], $history['current_player']);
    }

    public function makeAMove(int $field): void
    {
        // general
        $size = $this->board->getSize();
        $panel = $panel0 = $this->board->getPanel();
        $player = $this->getNowPlayerTurn();

        // from move to end
        if ($field != ($size - 1)) {
            $forward = array_slice($panel, $field, -1, true);
            $check = $this->assignFieldsTo($forward, $panel[$field], $player, false);
//            echo '<pre>';print_r([__CLASS__,__LINE__,__METHOD__,
//                $field, $size, $panel, $player,
//                $forward, $check
//            ]);echo '</pre>';die();
            if ($check['mark']) {
                for ($i = ($field + 1); $i <= $check['until']; $i++) {
                    $panel[$i] = $player;
                }
            }
        }
        // from init to move
        if ($field != 0) {
            $backward = array_slice($panel, 0, $field, true);
            $check = $this->assignFieldsTo($backward, $panel[$field], $player, true);
            if ($check['mark']) {
                for ($i = $check['until']; $i < $field; $i++) {
                    $panel[$i] = $player;
                }
            }
        }

        $panel[$field] = $player->getId();
        $this->board = Board::createFromBoard($panel);
    }

    private function assignFieldsTo($forward, $case, $player, $reverse = true)
    {
        if ($reverse) {
            $forward = array_reverse($forward, true);
        }
        $purity = false;
        $limit = 0;
        foreach ($forward as $position => $cell) {
            if ($cell != $case) {
                if ($cell == $player) {
                    $limit = $position;
                    $purity = true;
                }
                break;
            }
        }
        return [
            'mark' => $purity,
            'until' => $limit
        ];
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

    public function checkCurrentPlayerWins(): bool
    {
        $currentPlayerId = $this->getNowPlayerTurn()->getId();
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

    public function toArray()
    {
        return [
            'board' => $this->getBoard(),
            'player_one' => $this->getPlayerOne(),
            'player_two' => $this->getPlayerTwo(),
            'current_player' => $this->getNowPlayerTurn()
        ];
    }
}