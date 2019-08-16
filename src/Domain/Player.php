<?php

namespace Mayordomo\Domain;

class Player implements ValueObject
{
    private $name;
    private $position;

    private function __construct(string $name, int $position)
    {
        $this->name = $name;
        $this->position = $position;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function getId(): string
    {
        return $this->getPosition() % 2 ? Board::SPACE_PLAYER_ONE : Board::SPACE_PLAYER_TWO;
    }

    public static function create(string $name, int $position): self
    {
        return new self($name, $position);
    }

    public function equals(ValueObject $object): bool
    {
        /** @var self $object */
        return get_class($this) === get_class($object)
            && $this->name === $object->getName()
            && $this->position === $object->getPosition()
            && $this->getId() === $object->getId();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'position' => $this->getPosition(),
        ];
    }
}