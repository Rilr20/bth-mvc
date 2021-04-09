<?php

/**
 * yatzy view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

$header = $header ?? null;
$message = $message ?? null;
$tabledata = $tabledata ?? null;
$computerDice = $computerDice ?? null;
$playerDice = $playerDice ?? [];
$throws = $_SESSION["throws"] ?? null;
$throws = 3 - $throws;
?><h1><?= $header ?></h1>

<p><?= $message ?></p>

<p>yatzi som fan</p>
<form action="" method="POST">
    <div class="yatzy-box">
        <table class="yatzy-table">
            <tr>
                <th></th>
                <th>Player</th>
                <th>Computer</th>
            </tr>
            <?= $tabledata ?>
        </table>
        <div class="dice-box player">
            <?php

            foreach ($playerDice as $die) {
                echo $die;
                echo "<input type='checkbox' name='chosenDice[]' value='" .  substr_count($die, "<span class='dot'></span>") . "'>";
                echo "<input type='hidden' name='Dice[]' value=" .  substr_count($die, "<span class='dot'></span>") . "'>";
            }
            ?>
        </div>

        <div class="dice-box">
            <?= $computerDice ?>
        </div>
    </div>
    <h1><?= $throws ?> Throws Left</h1>
    <!-- <input type="hidden" name="playerDice" value="<?= $playerDice ?>"> -->
    <!-- <input type="hidden" name="tabledata" value="<?= $tabledata ?>"> -->
    <button class="game-button" value="roll" name="gameaction">Throw Dice</button>
    <?php if ($playerDice != null) { ?>
        <button class="game-button" value="reroll" name="gameaction">Reroll Dice</button>
        <button class="game-button" value="confirm" name="gameaction">Add to array</button>
    <?php } ?>
</form>