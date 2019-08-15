<?php
namespace Mayordomo\Infrastructure\Persistence\Session;

use Mayordomo\Domain\GameRepository;
use Mayordomo\Domain\Game;

class SessionGameRepository implements GameRepository
{
    private const ID = 'game';

    private $item;

    public function save(Game $game): void
    {
    }

    public function get(): ?Game
    {
        return null;
    }

    public function delete(): void
    {
    }
}
