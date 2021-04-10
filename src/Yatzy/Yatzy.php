<?php

declare(strict_types=1);

namespace Rilr\Yatzy;

use phpDocumentor\Reflection\PseudoTypes\True_;
use Rilr\Dice\{
    DiceHand,
    GraphicalDice
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
 * Class Yatzy.
 */
class Yatzy
{

    public $player;
    public $playerScore;
    public $computer;
    public $computerScore;
    public $recentDice;
    const DICEAMOUNT = 5;
    const DICESIDES = 6;
    const TABLEDATA = ["Ones", "Twos", "Threes", "Fours", "Fives", "Sixes", "Sum", "Bonus", "Three of a kind", "Four of a kind", "Full House", "Small Straight", "Large Straight", "Chance", "Yatzy", "Total Score"];
    const BORDER = ["Sum","Three of a kind", "Total Score"];
    public function render($playerScore, $computerScore): array
    {
        $loopdata = "";
        $data = [
            "header" => "Yatzy page!",
            "message" => "Hello, this is the Yatzy page, rendered as a layout."
        ];
        $i = 0;
        $htmlClass =  "<tr class='border'>";
        foreach (self::TABLEDATA as $row) {
            // if (in_array($row, self::BORDER)) {
            //     $loopdata = $loopdata . "<tr class='border'>";
            // } else {
            // }
            // $loopdata = $loopdata . "<tr>";
            in_array($row, self::BORDER) == true ? $loopdata .= $htmlClass : $loopdata .= "<tr>";
            // in_array($row, self::BORDER) == false ? 'true' : $loopdata = $loopdata . "<tr>";
            $loopdata = $loopdata . "<td>" . $row . "</td>";
            $loopdata = $loopdata . "<td>" . $playerScore[$i] . "</td>";
            $loopdata = $loopdata . "<td>" . $computerScore[$i] . "</td>";
            $loopdata = $loopdata . "</tr>";
            $i++;
        }
        $data["tabledata"] = $loopdata;
        return $data;
    }

    public function startYatzy(): array
    {
        $this->player = new DiceHand(self::DICEAMOUNT, self::DICESIDES);
        $this->computer = new DiceHand(self::DICEAMOUNT, self::DICESIDES);

        // $_SESSION["player"] = serialize($this->player);
        // $_SESSION["computer"] = serialize($this->computer);
        $this->computerScore = array_fill(0, 16, 0);
        $this->playerScore = $this->playerArray();
        $data = self::render($this->playerScore, $this->computerScore);
        $_SESSION["throws"] = 0;
        return $data;
    }

    private function playerArray(): array
    {
        $playerScore = [];
        $nobox = [6, 7, 15];
        for ($i = 0; $i < 16; $i++) {
            $playerScore[$i] = "X";
            if (in_array($i, $nobox)) {
                // $playerScore[$i] = "<input type='radio' name='choice' value='" . $i ."'>";
                // array_push($playerScore, 0);
                $playerScore[$i] = 0;
            }
        }
        // array_push($playerScore, 0);
        return $playerScore;
    }

    public function diceReturn()
    {
        $this->player->throw();
        $this->recentDice = $this->player->getLastRoll();
        $_SESSION["recentDice"] = $this->recentDice;
        if (!isset($_SESSION["playerScore"])) {
            $_SESSION["playerScore"] = $this->playerScore;
        }

        $this->playerScore = $_SESSION["playerScore"];
        $graphicalDice = new GraphicalDice(self::DICESIDES);
        $diceArray = [];
        foreach ($this->recentDice as $die) {
            array_push($diceArray, $graphicalDice->renderDice($die));
        }
        $data = $this->render($this->playerScore, $this->computerScore);
        $data["playerDice"] = $diceArray;
        return $data;
    }

    public function reroll($chosendie)
    {
        if (!isset($_SESSION["playerScore"])) {
            $_SESSION["playerScore"] = $this->playerScore;
        }
        $this->playerScore = $_SESSION["playerScore"];

        $difference = self::DICEAMOUNT - count($chosendie);
        $this->player->throw();
        $darray = $this->player->getLastRoll();
        $newArray = array_slice($darray, 0, $difference);
        $dicearray = array_merge($chosendie, $newArray);
        $graphicalDice = new GraphicalDice(self::DICESIDES);
        $dices = [];
        foreach ($dicearray as $die) {
            array_push($dices, $graphicalDice->renderDice($die));
        }
        $data = $this->render($this->playerScore, $this->computerScore);
        $data["playerDice"] = $dices;
        return $data;
    }

    public function addScore($diceScore)
    {
        // echo "function";
        // var_dump($diceScore);
        // var_dump($this->playerScore);
        $this->playerScore = $_SESSION["playerScore"];
        $next = $this->getNextScore();
        // var_dump($this->playerScore);
        // var_dump($diceScore);
        switch ($next) {
            case 0:
                # add 1s
                $this->simpleAdds($diceScore, 1);
                break;
            case 1:
                # add 2s
                $this->simpleAdds($diceScore, 2);
                break;
            case 2:
                # add 3s
                $this->simpleAdds($diceScore, 3);
                break;
            case 3:
                # add 4s
                $this->simpleAdds($diceScore, 4);
                break;
            case 4:
                # add 5s
                $this->simpleAdds($diceScore, 5);
                break;
            case 5:
                # add 6s
                $this->simpleAdds($diceScore, 6);

                break;
        }
        $this->calculateSum();
        $_SESSION["playerScore"] = $this->playerScore;
        return $this->render($this->playerScore, $this->computerScore);
        //gör arraysaker
        // var_dump($next);
    }

    private function getNextScore()
    {
        $i = 0;
        foreach ($this->playerScore as $score) {
            if ((string)$score == "X") {
                break;
            }
            $i++;
        }
        return $i;
    }

    private function simpleAdds($dices, $number)
    {
        $sum = 0;
        foreach ($dices as $dice) {
            if ($dice == $number) {
                $sum = $sum + (int)$dice;
            }
        }
        // return $sum;
        $this->playerScore[$number - 1] = $sum;
    }

    private function bonusScore($sum)
    {
        if ($sum >= 63) {
            $this->playerScore[7] = 50;
        }
    }
    private function calculateSum()
    {
        $scoreTable = $this->playerScore;
        $totalSum = 0;
        for ($i = 0; $i < 6; $i++) {
            if ($scoreTable[$i] != "X") {
                $totalSum = $totalSum + $scoreTable[$i];
            }
        }
        $this->bonusScore($totalSum);
        $this->playerScore[6] = $totalSum;
    }
}
