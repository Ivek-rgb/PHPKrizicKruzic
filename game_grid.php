<div class="playing-grid">
    <?php 
        for($i = 0; $i < 9; $i++)
                echo '<input type="submit" class="play-btn ' . markWithCurrentSymbol($i) . '" name="cell" value="' . $i . '" ' . checkForDisabled($i) . '>';
    ?>
</div>
<div id="winningSlab"></div>
<div class="buttonDiv">
    <input type="submit" id="reset" name="resetBtn" value="Reset">
    <input type="submit" id="continue" name="continueBtn" value="Continue" <?php if(isset($_SESSION["finished"]) && $_SESSION["finished"] == -1) echo "disabled"; ?>>
</div>