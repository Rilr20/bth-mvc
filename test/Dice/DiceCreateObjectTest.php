<?php

namespace Rilr\Dice;

use PHPUnit\Framework\TestCase;

/**
 * test cases for class Dice
 */
class DiceCraeteObjectTest extends TestCase
{
    public function testCreateDice() 
    {
        $sides = 6;
        $dice = new dice($sides);
        
        $res = $dice->sides;
        $exp = $sides;
        $this->assertEquals($exp, $res);
    }

    public function testGetLastRollNoRolls()
    {
        $dice = new dice(1);

        $res = $dice->getLastRoll();
        $exp = 0;
        $this->assertEquals($exp, $res);
    }
    public function testThrowDice() {
        $sides = 6;
        $dice = new dice($sides);

        $exp = $dice->getLastRoll();
        $res = $dice->throw();
        $this->assertNotEquals($exp, $res);
    }
}