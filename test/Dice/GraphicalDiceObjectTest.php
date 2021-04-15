<?php

namespace Rilr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * test cases for class Dice
 */
class GraphicalDiceCreateObjectTest extends TestCase
{
    public function testGraphicalDiceRender()
    {
        $sides = 6;
        $diceRes = 1;
        $graphicalDice = new GraphicalDice($sides);
        $exp = "";

        $res = $graphicalDice->renderDice($diceRes);
        $span = "<span class='dot'></span>";
        $exp = "<div class='face'>";
        $end = '</div>';
        for ($i = 0; $i < $diceRes; $i++) {
            $exp = $exp . $span;
        }
        $exp = $exp . $end;
        $this->assertEquals($exp, $res);
    }
}
