Pseuedocode
---
___
<pre>
class yatzi:
    function render(): array
        returns data array with ul element
    
    function reroll(reroll_list):
        for dice in dicehand
            if reroll is dicehand last roll
                remove reroll from reroll_list
                roll die

        return last thrown dice

    function StartYatzy(): array
        playerScore = [ html for checkbox ]
        computerScore = [ sixteen zeros ]

        playerDiceHand = new diceHand(5, 6)
        computerDiceHand = new diceHand(5, 6)
        $_session = playerDiceHand and computerDiceHand

        return render()

    
</pre>