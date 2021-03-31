<?php

/**
 * Standard view template to generate a simple web page, or part of a web page.
 */

declare(strict_types=1);

use Rilr\Dice\Game;

$header = $header ?? null;
$message = $message ?? null;
// var_dump($_SESSION);
// var_dump($_SESSION["running"]);
?>
<h1><?= $header ?></h1>
<p>Roll the die and get as close to 21 but not over it!</p>

<?php

if ($_SESSION["running"] == "true") { ?>
    <!--När denna är set så ska gameAction raderas och en ny knapp reset ska visas-->
    <p>Computer: <?= $computerSum ?></p>
    <p>Player: <?= $playerRoll ?></p>
    <form action="" method="post">
        <input type="submit" name="gameAction" value="roll" />
        <input type="hidden" name="computer" value="<?= $computerSum ?>">
        <input type="hidden" name="player" value="<?= $playerRoll ?>">
        <input type="submit" name="gameAction" value="stay" />
    </form>
    <?php
} else if ($_SESSION["running"] == "intermission") {
    ?>

    <h2><?= $gameText ?></h2>
    <p>Computer: <?= $computerSum ?></p>
    <p>Player: <?= $playerRoll ?></p>
    <form action="" method="post">
        <button name="gameAction" , type="submit" value="reset">Reset</button>
        <button name="gameAction" , type="submit" value="resetscore">Reset Score</button>
    </form>
    <?php
    if (isset($gameText)) {
        echo "<h3>Score History</h3>";
        echo "<ul>";
        foreach ($_SESSION["resultArray"] as $result) {
            echo "<li>" . $result . "</li>";
        }
        echo "</ul>";
    }

    ?>

    <?php
} else { //när progarmmet är nytt ?>
    <p>Play with 1 or 2 dice?</p>
    <form action="" method="post">
        <button name="options" type="submit" value="1">1 die</button>

        <button name="options" type="submit" value="2">2 Dice</button>
    </form>
    <?php
}
?>