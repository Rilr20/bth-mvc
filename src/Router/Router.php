<?php

declare(strict_types=1);

namespace Mos\Router;

use Rilr\Dice\{
    Game
};

use function Mos\Functions\{
    destroySession,
    redirectTo,
    renderView,
    renderTwigView,
    sendResponse,
    url
};

/**
 * Class Router.
 */
class Router
{
    public static function dispatch(string $method, string $path): void
    {
        if ($method === "GET" && $path === "/") {
            $data = [
                "header" => "Index page",
                "message" => "Hello, this is the index page, rendered as a layout.",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session") {
            $body = renderView("layout/session.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/session/destroy") {
            destroySession();
            redirectTo(url("/session"));
            return;
        } else if ($method === "GET" && $path === "/debug") {
            $body = renderView("layout/debug.php");
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/twig") {
            $data = [
                "header" => "Twig page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderTwigView("index.html", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/some/where") {
            $data = [
                "header" => "Rainbow page",
                "message" => "Hey, edit this to do it youreself!",
            ];
            $body = renderView("layout/page.php", $data);
            sendResponse($body);
            return;
        } else if ($method === "GET" && $path === "/dice") {
            if (!isset($_SESSION["game"])) {
                $callable = new Game();
                $_SESSION["game"] = serialize($callable);
            } else {
                $callable = unserialize($_SESSION["game"]);
            }

            $callable->initGame();
            return;
        } else if ($method === "POST" && $path === "/dice") {
            $callable = unserialize($_SESSION["game"]);
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
                        // player $_POST["player"];
                        $player = unserialize($_SESSION["player"]);
                        // var_dump($_SESSION);
                        // throws die or dice again
                        // $callable->playGame(1);
                        // var_dump($_POST["diceArray"]);
                        $callable->playerRoll($player, $_POST["player"], $_POST["computer"], $_SESSION["computerDice"]);
                        break;

                    case "stay":
                        // echo "stay";
                        // echo $_POST["computer"];
                        $computer = unserialize($_SESSION["computer"]);
                        // var_dump($_SESSION);
                        // computer does it thing :D
                        $callable->computerRoll($computer, $_POST["computer"], $_POST["player"]);
                        break;
                    case "reset":
                        unset($_SESSION["computerDice"]);
                        if (!isset($_SESSION["game"])) {
                            $callable = new Game();
                            $_SESSION["game"] = serialize($callable);
                        } else {
                            $callable = unserialize($_SESSION["game"]);
                        }

                        $callable->initGame();
                        // echo "reset game";
                        break;
                    case "resetscore":
                        // echo "reset score     ";
                        $_SESSION["resultArray"] = [];
                        if (!isset($_SESSION["game"])) {
                            $callable = new Game();
                            $_SESSION["game"] = serialize($callable);
                        } else {
                            $callable = unserialize($_SESSION["game"]);
                        }
                        $callable->initGame();
                        break;
                }
            }
            return;
        }

        $data = [
            "header" => "404",
            "message" => "The page you are requesting is not here. You may also checkout the HTTP response code, it should be 404.",
        ];
        $body = renderView("layout/page.php", $data);
        sendResponse($body, 404);
    }
}
