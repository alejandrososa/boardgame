<?php

namespace Mayordomo\Test\Integration\Application;

use Mayordomo\Application\ContinueGameStarted;
use Mayordomo\Application\ContinueGameStartedHandler;
use Mayordomo\Domain\Board;
use Mayordomo\Domain\Game;
use Mayordomo\Domain\GameRepository;
use Mayordomo\Test\Common\Domain\PlayerMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContinueGameStartedHandlerTest extends TestCase
{
    /**
     * @var ContinueGameStartedHandler
     */
    private $handler;

    /**
     * @var GameRepository|MockObject
     */
    private $repository;

    protected function setUp()
    {
        $this->handler = new ContinueGameStartedHandler($this->repository());
    }

    protected function tearDown()
    {
        $this->handler = null;
        $this->repository = null;
    }

    /**
     * @return GameRepository|MockObject
     */
    protected function repository()
    {
        return $this->repository = $this->repository ?: $this->createMock(GameRepository::class);
    }

    /**
     * @return Game
     */
    private function getGame(string $namePlayerOne, string $namePlayerTwo): Game
    {
        $game = Game::startNewGame(
            Board::createWithRandomSize(),
            PlayerMother::create($namePlayerOne, 1),
            PlayerMother::create($namePlayerTwo, 2)
        );
        return $game;
    }

    public function test_validate_that_save_a_game()
    {
        $command = new ContinueGameStarted(4);

        $game = $this->getGame('Player 1', 'Player 2');

        $this->repository
            ->expects($this->atLeastOnce())
            ->method('get')
            ->willReturn($game);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->willReturn(null);

        $gameStored = $this->handler->handle($command);

        $this->assertInstanceOf(Game::class, $gameStored);
    }
}
