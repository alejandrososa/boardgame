<?php

namespace Mayordomo\Domain;

interface Entity
{
    public function sameIdentityAs(Entity $other): bool;
    public function toArray(): array;
}