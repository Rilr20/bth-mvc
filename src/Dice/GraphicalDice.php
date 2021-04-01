<?php

declare(strict_types=1);

namespace Rilr\Dice;

use Rilr\Dice\Dice;

class GraphicalDice extends Dice
{
    public function renderDice($diceResult): string
    {
        $span = "<span class='dot'></span>";
        $htmlDice = "<div class='face'>";
        $end = '</div>';
        for ($i = 0; $i < $diceResult; $i++) {
            $htmlDice = $htmlDice . $span;
        }
        $htmlDice = $htmlDice . $end;
        return $htmlDice;
    }
}
