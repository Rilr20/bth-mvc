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
    <!-- när datorn kör så ska det visas många tärningar, när man kastar igen så ska tärningar visas  -->
    <!-- Spara förra täringarna för $computerDice -->
    <h3>Computer: <?= $computerSum ?></h3>
    <div class="dice-box">
        <?php
        // echo $computerDice;
        foreach ($computerDice as $die) {
            echo $die;
        }
        ?>
    </div>
    <h3>Player: <?= $playerRoll ?></h3>
    <div class="dice-box">
        <?php
        foreach ($playerDice as $die) {
            echo $die;
        }
        ?>
    </div>
    <form action="" method="post">
        <button class="game-button" type="submit" name="gameAction" value="roll" />Roll</button>
        <input type="hidden" name="computer" value="<?= $computerSum ?>">
        <input type="hidden" name="player" value="<?= $playerRoll ?>">
        <?php $_SESSION["computerDice"] = serialize($computerDice); ?>
        <button class="game-button" type="submit" name="gameAction" value="stay" />Stay</button>
    </form>
    <?php
} else if ($_SESSION["running"] == "intermission") { //When you've stayed
    ?>

    <h2><?= $gameText ?></h2>
    <h3>Computer: <?= $computerSum ?></h3>
    <div class="dice-box">
        <?php
        foreach ($computerDice as $die) {
            echo $die;
        }
        ?>
    </div>
    <h3>Player: <?= $playerRoll ?></h3>
    <div class="dice-box">
        <?php
        foreach ($playerDice as $die) {
            echo $die;
        }
        ?>
    </div>
    <div class="dice-box"></div>
    <form action="" method="post">
        <button class="game-button" name="gameAction" , type="submit" value="reset">Reset</button>
        <button class="game-button" name="gameAction" , type="submit" value="resetscore">Reset Score</button>
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
} else { //When program starts
    ?>
    <p>Play with 1 or 2 dice?</p>
    <form action="" method="post">
        <button class="game-button" name="options" type="submit" value="1">1 Die</button>
        <button class="game-button" name="options" type="submit" value="2">2 Dice</button>
    </form>
    <?php
}
?>