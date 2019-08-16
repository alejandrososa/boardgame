<?php

namespace Mayordomo\Test\Common\Domain;

use Mayordomo\Domain\Player;

final class PlayerMother
{
    public static function create(string $name, int $position): Player
    {
        return Player::create($name, $position);
    }

    public static function random(): Player
    {
        $players = [
            ['position'=> 1, 'name' => 'Arturo'],
            ['position'=> 2, 'name' => 'Mariela'],
            ['position'=> 1, 'name' => 'Pedro'],
            ['position'=> 2, 'name' => 'Isabel']
        ];

        $player = $players[array_rand($players)];

        return self::create($player['name'], $player['position']);
    }
}