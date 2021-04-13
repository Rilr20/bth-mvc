<?php

namespace Rilr\Yatzy;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use ReflectionClass;

/**
 * test cases for class Yatzy
 */
class YatzyCreateObjectTest extends TestCase
{
    public function testCreateYatzy()
    {
        $yatzy = new Yatzy();
        $this->assertInstanceOf("\Rilr\Yatzy\Yatzy", $yatzy);
    }

    public function testYatzyRender()
    {
        $yatzy = new Yatzy();

        $array = array_fill(0, 16, 0);
        $exp = 3;
        $res = $yatzy->render($array, $array);
        
        $this->assertEquals($exp, count($res));
        $this->assertEquals(true, isset($res["header"]));
        $this->assertEquals(true, isset($res["message"]));
        $this->assertEquals(true, isset($res["tabledata"]));
    }

    public function testPlayerArray()
    {
        $expArray = ["X", "X", "X", "X", "X", "X", 0, 0, "X", "X", "X", "X", "X", "X", "X", 0];
        $yatzy = new Yatzy();
        $reflector = new ReflectionClass('Rilr\Yatzy\Yatzy');
        $method = $reflector->getMethod('playerArray');
        $method->setAccessible(true);

        $res = $method->invokeArgs($yatzy, array());
    
        for ($i=0; $i < count($res); $i++) { 
            $this->assertEquals($expArray[$i], $res[$i]);
        }
    }

    public function testStartYatzy()
    {
        $yatzy = new Yatzy();
        $yatzy->startYatzy();

        $this->assertEquals(16, count($yatzy->computerScore));
        $this->assertEquals(16, count($yatzy->playerScore));
        $this->assertInstanceOf("\Rilr\Dice\DiceHand", $yatzy->player);
        $this->assertInstanceOf("\Rilr\Dice\DiceHand", $yatzy->computer);
    }
    public function testBonusScore()
    {
        $yatzy = new Yatzy();
        $reflector = new ReflectionClass('Rilr\Yatzy\Yatzy');
        $method = $reflector->getMethod('bonusScore');
        $method->setAccessible(true);
        $array = array_fill(0, 16, 0);
        $yatzy->playerScore = $array;

        $method->invokeArgs($yatzy, array(50));
        $this->assertEquals($yatzy->playerScore[0], 0);

        $method->invokeArgs($yatzy, array(63));
        $this->assertEquals($yatzy->playerScore[7], 50);
    }
    public function testCalculateSum() 
    {
        $expArray = ["2", "2", "2", "2", "2", "2", 0, 0, "X", "X", "X", "X", "X", "X", "X", 0];
        $yatzy = new Yatzy();
        $reflector = new ReflectionClass('Rilr\Yatzy\Yatzy');
        $method = $reflector->getMethod('calculateSum');
        $method->setAccessible(true);
        $yatzy->playerScore = $expArray;

        $method->invokeArgs($yatzy, array());
        $this->assertEquals(12, $yatzy->playerScore[6]);
    }
    public function testDiceReturn()
    {
        $yatzy = new Yatzy();
        $data = $yatzy->startYatzy();
        $data = $yatzy->diceReturn();

        $this->assertEquals(5, count($data["playerDice"]));
        $this->assertEquals(true, isset($_SESSION["playerScore"]));
    }
    public function testReroll()
    {
        $yatzy = new Yatzy();
        $data = $yatzy->startYatzy();
        $chosenDice = [[1], [2,2], [3,3,3], [4,4,4,4], [5,5,5,5,5]];

        foreach ($chosenDice as $diceArray) {
            $data = $yatzy->reroll($diceArray);
            $this->assertEquals(5, count($data["playerDice"]));
            $this->assertEquals(true, isset($_SESSION["playerScore"]));
            unset($_SESSION["playerScore"]);
        }
    }
    public function testAddScore()
    {
        $yatzy = new Yatzy();
        $yatzy->startYatzy();
        $_SESSION["playerScore"] = $yatzy->playerScore;
        $diceScore = [2,3,4,5,6];
        $exp = [0,2,3,4,5,6];
        $expTotalScore = [0,2,5,9,14,20];
        

        for ($i=0; $i < 6; $i++) { 
            $yatzy->addScore($diceScore);
            $this->assertEquals($exp[$i], $yatzy->playerScore[$i]);
            $this->assertEquals($expTotalScore[$i], $yatzy->playerScore[6]);
        }
    }
    public function testGetNextScore()
    {
        $playerScore = ["X", 0, "X", 0, "X", "X", 0, 0];
        $yatzy = new Yatzy();
        $reflector = new ReflectionClass('Rilr\Yatzy\Yatzy');
        $method = $reflector->getMethod('getNextScore');
        $method->setAccessible(true);
        $yatzy->playerScore = $playerScore;

        $exp = [0, 2, 4, 5];
        for ($i = 0; $i < count($exp); $i++) {
            $res = $method->invokeArgs($yatzy, array());
            $this->assertEquals($exp[$i], $res);
            $yatzy->playerScore[$res] = 0;
        }
    }

    public function testSimpleAdds()
    {
        $playerScore = ["X", "X", "X", "X", "X", "X", 0, 0, "X", "X", "X", "X", "X", "X", "X", 0];
        $yatzy = new Yatzy();
        $reflector = new ReflectionClass('Rilr\Yatzy\Yatzy');
        $method = $reflector->getMethod('simpleAdds');
        $method->setAccessible(true);
        $yatzy->playerScore = $playerScore;

        $dices = ["1", "1", "2", "5", "3"];
        $number = [1, 2, 3, 4, 5];
        $exp = [2, 2, 3, 0, 5];
        for($i = 0; $i < count($number); $i++){
            $method->invokeArgs($yatzy, array($dices, $number[$i]));
            $this->assertEquals($exp[$i], $yatzy->playerScore[$i]);
        }
    }
}