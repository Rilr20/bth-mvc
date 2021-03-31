<?php

declare(strict_types=1);

namespace Rilr\Dice;

use DateTime;
use Rilr\Dice\{
    Dice,
    DiceHand
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
 * Class Game
 */
class Game
{
    public $player;
    public $computer;
    public function playGame($numOfDie = 1): void
    {
        $_SESSION["running"] = "true";
        $data = [
            "title" => "Game 21",
            "header" => "Game 21",
            "messag" => "Hey!"
        ];
        // creates player and computer hands
        // $playerHand = new DiceHand(2, 6);
        // var_dump($numOfDie);
        $this->player = new DiceHand($numOfDie, 6);
        $this->computer = new DiceHand($numOfDie, 6);
        // $computerHand = new DiceHand(2, 6);
        // $die = new Dice(6);
        // $die->throw();

        //throws dice
        $newData = $this->firstRound($this->player, $this->computer);
        $data = array_merge($data, $newData);
        // $res = $this->dieSum($computerHand->getLastRoll());
        // $data["dielastRoll"] = $res;
        // $res = $this->dieSum($playerHand->getLastRoll());
        // $data["diceHandRoll"] = $res;

        //creates data for router
        // $data["debug_sesion"] = $_SESSION;
        // var_dump($data);
        $_SESSION["player"] = serialize($this->player);
        $_SESSION["computer"] = serialize($this->computer);
        $body = renderView("layout/dice.php", $data);
        sendResponse($body);
    }

    private function dieSum($diceArray): int
    {
        //ska ha föregående kast resultat
        $dieSum = 0;
        foreach ($diceArray as $die) {
            $dieSum = $dieSum + $die;
        }
        return $dieSum;
    }

    public function firstRound($playerHand, $computerHand): array
    {
        $data = [
            "title" => "Game 21",
        ];

        // roll dice
        $playerHand->throw();
        $computerHand->throw();

        // get rolls
        $res = $this->dieSum($computerHand->getLastRoll());
        $data["computerSum"] = $res;
        $res = $this->dieSum($playerHand->getLastRoll());
        $data["playerRoll"] = $res;

        return $data;
    }

    public function initGame(): void
    {
        $_SESSION["running"] = "false";

        $data = [
            "title" => "Game 21",
            "header" => "Game 21",
            "message" => "Hey!"
        ];

        $body = renderView("layout/dice.php", $data);
        sendResponse($body);
    }

    public function playerRoll($playerHand, $currentSum, $opponentSum): void
    {
        $newSum = 0;
        $playerHand->throw();
        $sumArray = $playerHand->getLastRoll();
        foreach ($sumArray as $roll) {
            $newSum = $newSum + $roll;
        }
        $newSum = $newSum + $currentSum;
        $data = [
            "title" => "Game 21",
            "header" => "Game 21",
            "messag" => "Hey!",
            "computerSum" => $opponentSum,
            "playerRoll" => $newSum
        ];
        $body = renderView("layout/dice.php", $data);
        sendResponse($body);
    }

    public function checkWinCondition($playerSum, $computerSum): void
    {
        $string = "";
        $pWin = "Player Wins!";
        $pLoss = "Player Loses!";
        if (!isset($_SESSION["resultArray"])) {
            $_SESSION["resultArray"] = [];
        }
        $newResult = [];
        if ($computerSum == 21) {
            $string = $pLoss;//"datorn har 21 förlust"; // funkar $pLoss
        } else if ($playerSum == 21) {
            $string = $pWin;//"spelaren har 21 vinst"; // funkar $pWin
        } else if ($playerSum > 21) {
            $string = $pLoss;//"spelaren har över 21 förlust"; // funkar $pLoss
        } else if ($computerSum > 21) {
            $string = $pWin;//"datorn har över 21 vinst"; // funkar $pWin
        } else if ($computerSum > $playerSum && $computerSum < 21) {
            $string = $pLoss; //"datorn har högre än spelaren och mindre än 21 förlust"; // funkar $pLoss
        } else if ($playerSum > $computerSum && $playerSum < 21) {
            $string = $pWin;//"spelaren har högre än datorn men mindre än 21 Vinst"; // funkar $pWin
        } else if ($playerSum == $computerSum) {
            $string = $pLoss;
        }
        $data = [
            "title" => "Game 21",
            "header" => "Game 21",
            "messag" => "Hey!",
            "computerSum" => $computerSum,
            "playerRoll" => $playerSum,
            "gameText" => $string
        ];
        // return $data;
        $currentDate = new DateTime();
        // echo $currentDate->format('Y-m-d H:i:s');
        $string = $string . " " . $currentDate->format('Y-m-d H:i:s');
        $newResult[0] = $string;
        $_SESSION["resultArray"] = array_merge($_SESSION["resultArray"], $newResult);
        $_SESSION["running"] = "intermission";
        $body = renderView("layout/dice.php", $data);
        sendResponse($body);
    }

    public function computerRoll($computerHand, $computerSum, $playerSum)
    {
        $newSum = $computerSum;
        if ($playerSum <= 21) {
            while ($newSum <= 16) {
                $computerHand->throw();

                $sumArray = $computerHand->getLastRoll();
                foreach ($sumArray as $roll) {
                    $newSum = $newSum + $roll;
                }
            }
        }
        $this->checkWinCondition($playerSum, $newSum);
    }
}
