<?php

namespace Mayordomo\Ui\Controller;

use Exception;
use Mayordomo\Application\ContinueGameStarted;
use Mayordomo\Application\ContinueGameStartedHandler;
use Mayordomo\Application\CreateNewGame;
use Mayordomo\Application\CreateNewGameHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends BaseController
{
    public function startGame(Request $request)
    {
        $playerOne = $request->request->get('player_one', 'Player 1');
        $playerTwo = $request->request->get('player_two', 'Player 2');
        $boardSize = $request->request->getInt('board_size', 10);

        try {
            $handler = $this->container->get(CreateNewGameHandler::class);
            $results = $handler->handle(new CreateNewGame($playerOne, $playerTwo, $boardSize));
        } catch (Exception $e) {
            return $this->json(sprintf('Error: %s', $e->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($results->toArray());
    }

    public function playNextTurn(Request $request)
    {
        $field = $request->request->getInt('field', 1);

        try {
            $handler = $this->container->get(ContinueGameStartedHandler::class);
            $results = $handler->handle(new ContinueGameStarted($field));
        } catch (\Error $e) {
            return $this->json(sprintf('Error: %s', $e->getMessage()), Response::HTTP_BAD_REQUEST);
        }

        return $this->json($results->toArray());
    }
}