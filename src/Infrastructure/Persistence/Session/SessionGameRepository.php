<?php
namespace Mayordomo\Infrastructure\Persistence\Session;

use Mayordomo\Domain\Board;
use Mayordomo\Domain\GameRepository;
use Mayordomo\Domain\Game;
use Mayordomo\Domain\Player;

class SessionGameRepository implements GameRepository
{
    private const ID = 'game';

    public function save(Game $game): void
    {
        session_start();
        unset($_SESSION[self::ID]);
        $_SESSION[self::ID] = json_encode($game->toArray());
    }

    public function get(): ?Game
    {
        session_start();
        $history = $_SESSION[self::ID];

        if (empty($history)){
            throw new \Exception('Game not found');
        }

        $history = json_decode($history, true);
        $history = [
            'board' => Board::createFromBoard($history['board']['panel']),
            'player_one' => Player::create($history['player_one']['name'], $history['player_one']['position']),
            'player_two' => Player::create($history['player_two']['name'], $history['player_two']['position']),
            'current_player' => Player::create($history['current_player']['name'], $history['current_player']['position']),
        ];

        return Game::continueFromArray($history);
    }
}
