<?php

declare(strict_types=1);

namespace Rilr\Controller;

use LengthException;
use Nyholm\Psr7\Factory\Psr17Factory;
use phpDocumentor\Reflection\Types\Null_;
use Psr\Http\Message\ResponseInterface;
use Rilr\Yatzy\Yatzy;

use function Mos\Functions\renderView;

/**
 * Controller for the index route.
 */
class Yatzycontroller
{
    public function __invoke(): ResponseInterface
    {
        $psr17Factory = new Psr17Factory();
        $this->resetGame();
        $yatzy = new Yatzy();
        $data = $yatzy->startYatzy();
        $_SESSION["yatzy"] = serialize($yatzy);
        $body = renderView("layout/yatzy.php", $data);
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
    private function resetGame()
    {
        unset($_SESSION["throws"]);
        unset($_SESSION["playerScore"]);
    }
    public function gameActions()
    {
        $psr17Factory = new Psr17Factory();
        $yatzy = unserialize($_SESSION["yatzy"]);
        $data = [];
        // var_dump($_POST);
        if ($_POST["gameaction"] == "roll"  && $_SESSION["throws"] != 3) {
            $data = $yatzy->diceReturn();
            $_SESSION["throws"] = $_SESSION["throws"] + 1;
        } else if ($_POST["gameaction"] == "reroll" && $_SESSION["throws"] != 3 && isset($_POST["chosenDice"])) {
            $data = $yatzy->reroll($_POST["chosenDice"]);
            $_SESSION["throws"] = $_SESSION["throws"] + 1;
        } else if (isset($_POST["gameaction"])) {
            $data = $yatzy->addScore($_POST["Dice"]);
            $_SESSION["throws"] = 0;
        }
        $body = renderView("layout/yatzy.php", $data);
        return $psr17Factory
            ->createResponse(200)
            ->withBody($psr17Factory->createStream($body));
    }
}
