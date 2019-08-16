<?php

namespace Mayordomo\Domain;

interface ValueObject
{
    public function equals(ValueObject $object): bool;
    public function toArray(): array;
}
