<?php

namespace Mayordomo\Test\Unit\Domain;

use Mayordomo\Domain\Board;
use PHPUnit\Framework\TestCase;

/**
 * validate that I can create a board with a number of fields
 * validate that I can create a board with a random number of fields
 * validate that a panel is created without colored fields
 * validate that a random panel is created without colored fields
 * validate that 20 percent of fields are assigned to players
 */
class BoardTest extends TestCase
{
    private $sizeOfBoard;

    /**
     * @return array
     */
    public function getFakePanel(): array
    {
        return array_fill(Board::START_INDEX, $this->sizeOfBoard, Board::FIELD_WHITE);
    }

    protected function setUp()
    {
        $this->sizeOfBoard = 12;
    }

    public function test_validate_that_I_can_create_a_board_with_a_number_of_fields()
    {
        $board = Board::createWithSize($this->sizeOfBoard);
        $this->assertEquals($this->sizeOfBoard, $board->getSize());
    }

    public function test_validate_that_I_can_create_a_board_with_a_random_number_of_fields()
    {
        $board = Board::createWithRandomSize();
        $this->assertThat($board->getSize(),
            $this->logicalAnd(
                $this->greaterThanOrEqual(Board::DIMENSION_FROM),
                $this->lessThanOrEqual(Board::DIMENSION_TO)
            )
        );
    }

    public function test_validate_that_a_random_panel_is_created_without_colored_fields()
    {
        $board = Board::createWithRandomSize();
        $panelExpected = array_fill(Board::START_INDEX, $board->getSize(), Board::FIELD_WHITE);
        $this->assertSame($panelExpected, $board->getPanel());
    }

    public function test_validate_that_a_panel_is_created_without_colored_fields()
    {
        $board = Board::createWithSize($this->sizeOfBoard);
        $panelExpected = array_fill(Board::START_INDEX, $this->sizeOfBoard, Board::FIELD_WHITE);
        $this->assertSame($panelExpected, $board->getPanel());
    }

    public function test_validate_that_20_percent_of_fields_are_assigned_to_players()
    {
        $board = Board::createWithSize($this->sizeOfBoard);
        $board->assign20PercentOfFieldsToPlayers();
        $panelExpected = $this->getFakePanel();
        $this->assertNotSame($panelExpected, $board->getPanel());
        $this->assertContains(Board::SPACE_PLAYER_ONE, $board->getPanel());
        $this->assertContains(Board::SPACE_PLAYER_TWO, $board->getPanel());
    }
}
