<?php

namespace Mayordomo\Test\Unit\Infrastructure\Persistence;

use Mayordomo\Domain\Board;
use Mayordomo\Domain\Game;
use Mayordomo\Domain\GameRepository;
use Mayordomo\Infrastructure\Persistence\Session\SessionGameRepository;
use Mayordomo\Test\Common\Domain\PlayerMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Validate that saves a game
 * Validate that get a game
 */
class SessionGameRepositoryTest extends TestCase
{
    /**
     * @var GameRepository|MockObject
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = $this->createMock(SessionGameRepository::class);
    }

    protected function tearDown()
    {
        $this->repository = null;
    }

    public function test_save()
    {
        $game = $this->getGame();

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->equalTo($game))
            ->willReturn(null);

        $this->assertNull($this->repository->save($game));
    }

    public function testGet()
    {
        $game = $this->getGame();

        /** @var Game $gameStored */
        $this->repository
            ->expects($this->once())
            ->method('get')
            ->willReturn($game);

        $gameStored = $this->repository->get();

        $this->assertTrue($game->sameIdentityAs($gameStored));
    }

    /**
     * @return Game
     */
    private function getGame(): Game
    {
        $game = Game::startNewGame(
            Board::createWithRandomSize(),
            PlayerMother::random(),
            PlayerMother::random()
        );
        return $game;
    }
}
