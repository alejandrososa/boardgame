<?php

namespace Mayordomo\Test\Integration\Domain;

use DomainException;
use Mayordomo\Domain\Board;
use Mayordomo\Domain\Game;
use Mayordomo\Domain\Player;
use Mayordomo\Test\Common\Domain\PlayerMother;
use PHPUnit\Framework\TestCase;

/**
 * validate that a board and 2 players can start the game
 * Validate that you can choose first player randomly
 * validate that a player can move fill a field on the board
 * validate that when a player moves a field the game changes player
 * throw error if the game history is not valid to continue the game
 * validate that the game can be continued from a game started
 * check current player wins
 */
class GameTest extends TestCase
{
    private $playerOne;
    private $playerTwo;
    private $board;

    protected function setUp()
    {
        $this->board = Board::createWithRandomSize();
        $this->playerOne = PlayerMother::create('Mariela', 1);
        $this->playerTwo = PlayerMother::create('Pedro', 2);
    }

    protected function tearDown()
    {
        $this->board = null;
        $this->playerOne = null;
        $this->playerTwo = null;
    }

    public function getGameHistory(Player $currentPlayer): array
    {
        $history = [
            'board' => $this->board,
            'player_one' => $this->playerOne,
            'player_two' => $this->playerTwo,
            'current_player' => $currentPlayer
        ];
        return $history;
    }

    public function test_validate_that_a_board_and_2_players_can_start_the_game()
    {
        $game = Game::startNewGame($this->board, $this->playerOne, $this->playerTwo);
        $this->assertInstanceOf(Game::class, $game);
    }

    public function test_validate_that_you_can_choose_first_player_randomly()
    {
        $players = [$this->playerOne, $this->playerTwo];

        $game = Game::startNewGame($this->board, $this->playerOne, $this->playerTwo);
        $firstPlayer = $game->getNowPlayerTurn();

        $this->assertContains($firstPlayer, $players);
    }

    public function test_validate_that_a_player_can_move_fill_a_field_on_the_board()
    {
        $game = Game::startNewGame($this->board, $this->playerOne, $this->playerTwo);
        $emptySpacesBeforeChange = array_count_values($game->getBoard()->getPanel())[0];
        $game->makeAMove(5);
        $emptySpacesAfterChange = array_count_values($game->getBoard()->getPanel())[0];

        $this->assertLessThanOrEqual($emptySpacesBeforeChange, $emptySpacesAfterChange);
    }

    public function test_validate_that_when_a_player_moves_a_field_the_game_changes_player()
    {
        $game = Game::startNewGame($this->board, $this->playerOne, $this->playerTwo);
        $currentPlayerBeforePlay = $game->getNowPlayerTurn();
        $game->makeAMove(5);
        $game->nextPlayerTurn();
        $nextPlayerAfterPlay = $game->getNowPlayerTurn();

        $this->assertNotSame($currentPlayerBeforePlay, $nextPlayerAfterPlay);
    }

    public function test_throw_error_if_the_game_history_is_not_valid_to_continue_the_game()
    {
        $this->expectException(DomainException::class);

        $history = [];
        Game::continueFromArray($history);
    }

    public function test_validate_that_the_game_can_be_continued_from_a_game_started()
    {
        $gameStarted = Game::startNewGame($this->board, $this->playerOne, $this->playerTwo);
        $history = $this->getGameHistory($gameStarted->getNowPlayerTurn());
        $game = Game::continueFromArray($history);

        $this->assertTrue($gameStarted->sameIdentityAs($game));
        $this->assertTrue($gameStarted->getNowPlayerTurn()->equals($game->getNowPlayerTurn()));
    }

    public function test_check_current_player_wins()
    {
        $history = [
            'board' => Board::createFromBoard(array_fill(0, 10, $this->playerOne->getId())),
            'player_one' => $this->playerOne,
            'player_two' => $this->playerTwo,
            'current_player' => $this->playerOne
        ];
        $game = Game::continueFromArray($history);

        $this->assertTrue($game->checkCurrentPlayerWins());
    }
}
