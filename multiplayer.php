<?php
    session_start(); 

    require "./board_functions.php"; 

    if(!isset($_SESSION["cells"])){
        $_SESSION["cells"] = ["", "", "", "", "", "", "", "", ""]; 
        $_SESSION["points"] = ["circle" => 0, "cross" => 0];
        resetCounters(); 
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        if($_POST["resetBtn"] ?? "None" == "Reset"){
            resetCounters(); 
            resetBoard(); 
            $_SESSION["points"]["circle"] = 0;
            $_SESSION["points"]["cross"] = 0;
        } 

        if($_POST["continueBtn"] ?? "None" == "Continue"){
            resetCounters();
            resetBoard(); 
        }

        if(isset($_POST["cell"])){
            $_SESSION["finished"] = addToMarkedPositions($_SESSION["symbol"] == "cross" ? 1 : 50, $_POST["cell"], $_SESSION["addition"]);
            $_SESSION["cells"][$_POST["cell"]] = $_SESSION["symbol"]; 
        }


        if($_SESSION["finished"] == -1)
            if(!isset($_SESSION["symbol"])){
                $_SESSION["symbol"] = "cross";
            }else if($_SESSION["symbol"] == "cross") $_SESSION["symbol"] = "circle";
            else $_SESSION["symbol"] = "cross";

    }

    function markWithCurrentSymbol(int $idxBtn){
        return $_SESSION["cells"][$idxBtn]; 
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./tic-tac-toe.css"/>
    <title>Krizic Kruzic - MP</title>
    <style>
        <?php require "./win_bar_logic.php"; ?>
    </style>
</head>

<body>
    <div class="game">
        <h1>PHP Križić kružić - MP</h1>
        <h2><?php 
            echo "O:{$_SESSION["points"]["circle"]}\nX:{$_SESSION["points"]["cross"]}"; 
        ?></h2>
        <form method="POST" action="multiplayer.php" class="playingForm">
            <?php require "./game_grid.php"; ?> 
        </form>
        <?php 
            require "./modular_return.php"; 
            echo returnReturnForm("multiplayer.php");        
        ?>
    </div>
</body>
</html>