<?php

namespace Mayordomo\Application;

use Exception;
use Mayordomo\Domain\GameRepository;

class ContinueGameStartedHandler
{
    /**
     * @var GameRepository
     */
    private $repository;

    public function __construct(GameRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ContinueGameStarted $command)
    {
        try {
            $game = $this->repository->get();
            $game->makeAMove($command->getFieldToMark());
            $game->nextPlayerTurn();
            $this->repository->save($game);
            return $game;
        } catch (Exception $e){
            throw $e;
        }
    }
}