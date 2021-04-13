<?php

namespace Rilr\Dice;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

/**
 * test cases for class Dice
 */
class GraphicalDiceObjectTest extends TestCase
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
        assertEquals($exp, $res);
    }
}
