<?php

namespace Mayordomo\Domain;

interface GameRepository
{
    public function save(Game $game): void;
    public function get(): ?Game;
}