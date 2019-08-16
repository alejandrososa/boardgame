<?php

namespace Mayordomo\Application;

use Mayordomo\Domain\Board;
use Mayordomo\Domain\Game;
use Mayordomo\Domain\GameRepository;
use Mayordomo\Domain\Player;

class CreateNewGameHandler
{
    /**
     * @var GameRepository
     */
    private $repository;

    public function __construct(GameRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(CreateNewGame $command)
    {
        $board = $command->getBoardSize() !== 0
            ? Board::createWithSize($command->getBoardSize()) : Board::createWithRandomSize();
        $playerOne = Player::create($command->getNamePlayerOne(), 1);
        $playerTwo = Player::create($command->getNamePlayerTwo(), 2);

        $board->assign20PercentOfFieldsToPlayers();

        $game = Game::startNewGame($board, $playerOne, $playerTwo);
        $this->repository->save($game);

        return $game;
    }
}