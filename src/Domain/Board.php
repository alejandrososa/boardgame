<?php

namespace Mayordomo\Domain;

class Board implements ValueObject
{
    const DIMENSION_FROM = 10;
    const DIMENSION_TO = 12;
    const START_INDEX = 0;
    const FIELD_WHITE = 0;
    const DEFAULT_PERCENT_FIELDS = 0.2;
    const SPACE_PLAYER_ONE = 1;
    const SPACE_PLAYER_TWO = 2;

    private $size = 0;
    private $panel = [];

    private function __construct(int $size, ?array $panel = [])
    {
        $this->size = $size;
        $this->panel = $panel;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPanel(): array
    {
        return $this->panel;
    }

    public static function createFromBoard(array $panel)
    {
        $size = count($panel);
        return new self($size, $panel);
    }

    public static function createWithSize(int $size): self
    {
        $self = new self($size);
        $self->coloringBlankPanel();
        return $self;
    }

    public static function createWithRandomSize(): self
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $self = new self(random_int(self::DIMENSION_FROM, self::DIMENSION_TO));
        $self->coloringBlankPanel();
        return $self;
    }

    private function coloringBlankPanel()
    {
        $this->panel = array_fill(self::START_INDEX, $this->size, self::FIELD_WHITE);
    }

    public function assign20PercentOfFieldsToPlayers(): void
    {
        $fieldsAssignedToPlayers = round($this->size * self::DEFAULT_PERCENT_FIELDS, 0, PHP_ROUND_HALF_DOWN);
        $fieldsAssignedToPlayers = $fieldsAssignedToPlayers * 2;

        foreach ($this->panel as $key => &$field) {
            if ($key >= 1 && $key <= $fieldsAssignedToPlayers) {
                $field = $key % 2 === 0 ? self::SPACE_PLAYER_ONE : self::SPACE_PLAYER_TWO;
            }
        }

        shuffle($this->panel);
    }

    public function equals(ValueObject $object): bool
    {
        /** @var self $object */
        return get_class($this) === get_class($object)
            && $this->panel === $object->getPanel()
            && $this->size === $object->getSize();
    }

    public function toArray(): array
    {
        return [
            'size' => $this->getSize(),
            'panel' => $this->getPanel(),
        ];
    }
}