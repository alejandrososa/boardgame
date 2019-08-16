<?php
namespace Mayordomo\Infrastructure\Persistence\Session;

use Mayordomo\Domain\GameRepository;
use Mayordomo\Domain\Game;

class SessionGameRepository implements GameRepository
{
    private const ID = 'game';

    public function save(Game $game): void
    {
        unset($_SESSION[self::ID]);
        $_SESSION[self::ID] = json_encode($game->toArray());
    }

    public function get(): ?Game
    {
        $history = $_SESSION[self::ID];

        if (empty($history)){
            throw new \Exception('Game not found');
        }

        $history = json_decode($history);
        return Game::continueFromArray($history);
    }
}
