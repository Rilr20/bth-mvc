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
            // $data = [
            //     "header" => "Dice game",
            //     "message" => "Hey, edit this to do it youreself!",
            // ];
            // $body = renderView("layout/dice.php", $data);
            // sendResponse($body);
            // $callable = new \Rilr\Dice\Game();
            // $_SESSION["game"] = var_dump($callable);
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
            // echo "<pre>";
            // echo "tjenare ";
            // var_dump($_POST);
            // echo "<br>";
            // var_dump($_SESSION);
            // echo "<br>";
            // var_dump($_SESSION["game"]);
            // echo "</pre>";
            // // $callable = $_SESSION["game"];
            // var_dump($callable);
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
                        // echo "roll";
                        // player $_POST["player"];
                        $player = unserialize($_SESSION["player"]);
                        // var_dump($_SESSION);
                        // throws die or dice again
                        // $callable->playGame(1);
                        $callable->playerRoll($player, $_POST["player"], $_POST["computer"]);
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
            // $callable ->playGame();
            // $callable = $_SESSION["callable"];
            // $callable->playGame();
            // if (isset($_POST['button1'])) {
            //     //en tärning spelas det med
            //     $_SESSION["running"] = false;

            //     $data = [
            //             "header" => "Dice",
            //             "messag" => "Hejsan nu kör vi!"
            //         ];

            //     $body = renderView("layout/dice.php", $data);
            //     sendResponse($body);
            //     // echo "This is Button1 that is selected";
            //     // $callable->playGame();
            // }
            // if (isset($_POST['button2'])) {
            //     // två tärningar spelas det med
            //     echo "This is Button2 that is selected";
            //     // $newGame = new Game(2);
            // }
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
