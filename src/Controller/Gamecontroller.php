<?php

declare(strict_types=1);

namespace Rilr\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;
use Rilr\Dice\Game;

use function Mos\Functions\renderView;

/**
 * Controller for the index route.
 */
class Gamecontroller
{
    // public function __invoke(): ResponseInterface
    // {
    //     $psr17Factory = new Psr17Factory();

    //     $data = [
    //         "header" => "Index page",
    //         "message" => "Hello, this is the index page, rendered as a layout.",
    //     ];

    //     $body = renderView("layout/page.php", $data);

    //     return $psr17Factory
    //         ->createResponse(200)
    //         ->withBody($psr17Factory->createStream($body));
    // }
    public function index(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        // var_dump($_SESSION);
        if (!isset($_SESSION["game"])) {
            $callable = new Game();
            $_SESSION["game"] = serialize($callable);
        }
        $callable = unserialize($_SESSION["game"]);



        // var_dump($data);
        // $data = [
        //     "header" => "Rainbow page",
        //     "message" => "Hey, edit this to do it youreself!",
        // ];
        $data = $callable->initGame();
        $body = renderView("layout/dice.php", $data);

        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }

    public function gamelogic()
    {
        $callable = unserialize($_SESSION["game"]);
        $psr17Factory = new Psr17Factory();
        if (isset($_POST["options"])) {
            switch ($_POST["options"]) {
                case 1:
                    // echo "en tärning";
                    $callable->playGame(1);
                    break;

                case 2:
                    // echo "två tärningar";
                    $callable->playGame(2);
                    break;
            }
        } else if (isset($_POST["gameAction"])) {
            switch ($_POST["gameAction"]) {
                case "roll":
                    $player = unserialize($_SESSION["player"]);
                    $callable->playerRoll($player, $_POST["player"], $_POST["computer"], $_SESSION["computerDice"]);
                    break;

                case "stay":
                    $computer = unserialize($_SESSION["computer"]);
                    // computer does its thing :D
                    $callable->computerRoll($computer, $_POST["computer"], $_POST["player"]);
                    break;
                case "reset":
                    unset($_SESSION["computerDice"]);
                    if (!isset($_SESSION["game"])) {
                        $callable = new Game();
                        $_SESSION["game"] = serialize($callable);
                    }
                    $callable = unserialize($_SESSION["game"]);

                    $data = $callable->initGame();
                    $body = renderView("layout/dice.php", $data);

                    return $psr17Factory
                    ->createResponse(200)
                    ->withBody($psr17Factory->createStream($body));
                    // echo "reset game";
                    break;
                case "resetscore":
                    // echo "reset score     ";
                    $_SESSION["resultArray"] = [];
                    if (!isset($_SESSION["game"])) {
                        $callable = new Game();
                        $_SESSION["game"] = serialize($callable);
                    }
                    $callable = unserialize($_SESSION["game"]);
                    $data = $callable->initGame();
                    $body = renderView("layout/dice.php", $data);

                    return $psr17Factory
                    ->createResponse(200)
                        ->withBody($psr17Factory->createStream($body));
                    break;
            }
        }
        return;
    }
}
