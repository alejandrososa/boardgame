<?php

namespace Mayordomo\Test\Unit\Domain;

use Mayordomo\Domain\ValueObject;
use Mayordomo\Test\Common\Domain\PlayerMother;
use Mayordomo\Domain\Player;
use PHPUnit\Framework\TestCase;

/**
 * validate that I can create a player with name and with a turn
 * validate it can be compared
 */
class PlayerTest extends TestCase
{
    private $player;

    protected function setUp()
    {
        $this->player = PlayerMother::random();
    }

    protected function tearDown()
    {
        $this->player = null;
    }

    public function test_validate_that_I_can_create_a_player_with_name_and_with_a_turn()
    {
        $player = Player::create($this->player->getName(), $this->player->getPosition());
        $this->assertEquals($this->player->getName(), $player->getName());
        $this->assertEquals($this->player->getPosition(), $player->getPosition());
    }

    public function test_it_can_be_compared()
    {
        $first = PlayerMother::create('Mariela', 1);
        $second = PlayerMother::create('Mariela', 1);
        $third = PlayerMother::create('Martin', 2);
        $fourth = new class() implements ValueObject {
            public function getId() { return 'Z'; }
            public function getName() { return ''; }
            public function getPosition() { return 3; }
            public function equals(ValueObject $object): bool { return false; }
        };

        $this->assertTrue($first->equals($second));
        $this->assertFalse($first->equals($third));
        $this->assertFalse($first->equals($fourth));
    }
}
