<?php

namespace Mayordomo\Test\Integration\Application;

use Mayordomo\Application\CreateNewGame;
use Mayordomo\Application\CreateNewGameHandler;
use Mayordomo\Domain\GameRepository;
use Mayordomo\Test\Common\Domain\PlayerMother;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateNewGameHandlerTest extends TestCase
{
    /**
     * @var CreateNewGameHandler
     */
    private $handler;

    /**
     * @var GameRepository|MockObject
     */
    private $repository;

    protected function setUp()
    {
        $this->handler = new CreateNewGameHandler($this->repository());
    }

    protected function tearDown()
    {
        $this->handler = null;
    }

    /**
     * @return GameRepository|MockObject
     */
    protected function repository()
    {
        return $this->repository = $this->repository ?: $this->createMock(GameRepository::class);
    }

    public function test_validate_that_save_a_complaint()
    {
        $command = new CreateNewGame(
            PlayerMother::random()->getName(),
            PlayerMother::random()->getName()
        );

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->willReturn(null);

        $gameStored = $this->handler->handle($command);

        $this->assertEquals($command->getNamePlayerOne(), $gameStored->getPlayerOne()->getName());
        $this->assertEquals($command->getNamePlayerTwo(), $gameStored->getPlayerTwo()->getName());
    }
}
