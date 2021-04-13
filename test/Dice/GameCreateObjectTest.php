<?php

namespace Rilr\Dice;

use BadFunctionCallException;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * test cases for class Dice
 */
class GameCreateObjectTest extends TestCase 
{
    public function testInitGame() 
    {
        $game = new Game();
        $data = $game->initGame();
        $exp = [
            "title" => "Game 21",
            "header" => "Game 21",
            "message" => "Hey!"
        ];
        // session test
        $this->assertEquals("false", $_SESSION["running"]);
        $this->assertEquals($exp, $data);
    }

    public function testPlayGame() 
    {
        // unset($_SESSION["player"]);
        // unset($_SESSION["computer"]);
        // unset($_SESSION["running"]);

        $game = new Game();
        $numOfDie = 2;
        $data = $game->playGame($numOfDie);
        $player = unserialize($_SESSION["player"]);
        $computer = unserialize($_SESSION["computer"]);
        $playerRes = $player->getDiceHandLenght();
        $computerSes = $computer->getDiceHandLenght();
        
        $this->assertEquals(true, isset($_SESSION["player"]));
        $this->assertEquals(true, isset($_SESSION["computer"]));
        $this->assertEquals(true, isset($_SESSION["running"]));
        
        $this->assertEquals($numOfDie, $playerRes);
        $this->assertEquals($numOfDie, $computerSes);
    }

    public function testDieSum() 
    {
        $diceArray = [2, 4, 6, 7, 8, 2];
       
        
        $game = new Game();
        $reflector = new ReflectionClass('Rilr\Dice\Game');
        $method = $reflector->getMethod('dieSum');
        $method->setAccessible(true);

        $res = $method->invokeArgs($game, array($diceArray));

        // $res = $method->dieSum($diceArray);
        $exp = 29;
        $this->assertEquals($exp, $res);
    }

    public function testRenderDice() 
    {
        $diceArray = [2, 3];
        $game = new Game();
        $reflector = new ReflectionClass('Rilr\Dice\Game');
        $method = $reflector->getMethod('renderDice');
        $method->setAccessible(true);

        $res = $method->invokeArgs($game, array($diceArray));

        // $res = $method->dieSum($diceArray);
        $span = "<span class='dot'></span>";
        $exp = "<div class='face'>";
        $end = '</div>';
        for ($i = 0; $i < $diceArray[0]; $i++) {
            $exp = $exp . $span;
        }
        $exp = $exp . $end;

        $this->assertEquals($exp, $res[0]);
        $this->assertEquals(count($diceArray), count($res));
    }

    public function testFirstRound() 
    {
        $hand1 = new DiceHand(2,4);
        $hand2 = new DiceHand(2, 4);
        $game = new Game();
        $reflector = new ReflectionClass('Rilr\Dice\Game');
        $method = $reflector->getMethod('firstRound');
        $method->setAccessible(true);

        $res = $method->invokeArgs($game, array($hand1, $hand2));

        $this->assertNotEquals(0, $res["playerRoll"]);
        $this->assertNotEquals(0, $res["computerSum"]);

        // $this->assertNotEquals(0, $res["computerDice"]);
        // $this->assertNotEquals(0, $res["playerDice"]);

        $dataLength = 5;
        $this->assertEquals($dataLength, count($res));
    }

    public function testCheckWinCondition() 
    {
        $game = new Game();
        $pWin = "Player Wins!";
        $pLoss = "Player Loses!";
        $playerSum = [21, 21, 22, 10, 19, 20, 10];
        $computerSum = [21, 20, 10, 22, 20, 18, 10];
        $exp = [$pLoss, $pWin, $pLoss, $pWin, $pLoss, $pWin, $pLoss];
        $htmlArray = [];
        for($i = 0; $i < count($playerSum); $i++) {
            $res = $game->checkWinCondition($playerSum[$i], $computerSum[$i], $htmlArray);
            // $this->assertContains($exp[$i], $res["gameText"], "assert doesn't contain right game text");
            $this->assertEquals($exp[$i], $res["gameText"]);
            unset($_SESSION["resultArray"]);
        }
    }

    public function testPlayerRoll() 
    {
        $game = new Game();
        $hand1 = new DiceHand(2, 4);
        $currentSum = 5;
        $opponentSum = 10;
        $test = serialize(null);

        $res = $game->playerRoll($hand1, $currentSum, $opponentSum, $test);

        $this->assertGreaterThan($currentSum, $res["playerRoll"]);
        $this->assertEquals($opponentSum, $res["computerSum"]);
        $this->assertEquals(null, $res["computerDice"]);
    }
    public function testComputerRoll() 
    {
        $game = new Game();
        $hand1 = new DiceHand(2, 4);
        $playerSum = [5, 22];
        $computerSum = 5;

        $res = $game->computerRoll($hand1, $computerSum, $playerSum[0]);
        $this->assertGreaterThanOrEqual(16, $res["computerSum"]);
        $res = $game->computerRoll($hand1, $computerSum, $playerSum[1]);
        $this->assertEquals($computerSum, $res["computerSum"]);
    }
}