<?php

namespace Rilr\Controller;

use PHPUnit\Framework\TestCase;
use Rilr\Controller\Yatzycontroller;
use Rilr\Yatzy\Yatzy;
use FastRoute\RouteCollector;
use ReflectionClass;

/**
 * test cases for class YatzyController
 */
class YatzyControllerCreateObjectTest extends TestCase
{

    // public function testYatzyControllerInvoke()
    // {
    //     // $router = $router ?? new RouteCollector(
    //     //     new \FastRoute\RouteParser\Std(),
    //     //     new \FastRoute\DataGenerator\MarkBased()
    //     // );
    //     // $router->addRoute("GET", "/yatzy", "\Rilr\Controller\Yatzycontroller");
    //     // $yatzyController = new Yatzycontroller();
    //     // $res = $yatzyController->__invoke();
    //     // $this->assertEquals(true, isset($_SESSION["yatzy"]));
    //     $yatzyController = new Yatzycontroller();

    //     $yatzyController->__invoke();

    //     $this->assertEquals(true, isset($_SESSION["yatzy"]));
    // }
    public function testYatzyControllerResetGame()
    {
        $_SESSION["throws"] = "test";
        $_SESSION["playerScore"] = "test";

        $yatzyController = new Yatzycontroller();
        $reflector = new ReflectionClass('Rilr\Controller\Yatzycontroller');
        $method = $reflector->getMethod('resetGame');
        $method->setAccessible(true);

        $this->assertEquals(true, isset($_SESSION["throws"]));
        $this->assertEquals(true, isset($_SESSION["playerScore"]));

        $method->invokeArgs($yatzyController, array());
        // $yatzyController = new Yatzycontroller();
        // $yatzyController->gameActions();

        $this->assertEquals(false, isset($_SESSION["throws"]));
        $this->assertEquals(false, isset($_SESSION["playerScore"]));
    }
    // public function testYatzyControllerGameAction()
    // {
    //     $yatzyController = new Yatzycontroller();
    //     $yatzy = new Yatzy();
    //     $yatzy->startYatzy();
    //     $_SESSION["yatzy"] = serialize($yatzy);
    //     $throws = 0;
    //     $_SESSION["throws"] = $throws;

    //     $_POST["gameaction"] = "roll";
    //     $throws = $throws + 1;
    //     $yatzyController->gameActions();
    //     $this->assertEquals($throws, $_SESSION["throws"]);

    //     $_POST["gameaction"] = "reroll";
    //     $_POST["chosenDice"] = [1, 2];
    //     $throws = $throws + 1;
    //     $yatzyController->gameActions();
    //     $this->assertEquals($throws, $_SESSION["throws"]);

    //     $_POST["gameaction"] = "reroll";
    //     $throws = $throws + 1;
    //     $yatzyController->gameActions();
    //     $this->assertEquals(0, $_SESSION["throws"]);
    // }
}
