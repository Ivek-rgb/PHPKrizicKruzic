<?php
    session_start(); 

    require "./board_functions.php"; 

    if(!isset($_SESSION["cells"])){
        $_SESSION["cells"] = ["", "", "", "", "", "", "", "", ""]; 
        $_SESSION["points"] = ["circle" => 0, "cross" => 0];
        $_SESSION["moves"] = 0; 
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
            $_SESSION["moves"] = 0; 
        }

        if(isset($_POST["cell"])){
            $_SESSION["moves"]++;
            if($_SESSION["moves"] > 8){
                $_SESSION["finished"] = -2; 
            }else $_SESSION["finished"] = addToMarkedPositions($_SESSION["symbol"] == "cross" ? 1 : 50, $_POST["cell"], $_SESSION["addition"]);
            $_SESSION["cells"][$_POST["cell"]] = $_SESSION["symbol"];
        }

        if($_SESSION["finished"] == -1){
            if(!isset($_SESSION["symbol"])){
                $_SESSION["symbol"] = "cross";
            }else if($_SESSION["symbol"] == "cross") $_SESSION["symbol"] = "circle";
            else $_SESSION["symbol"] = "cross";

        }

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
        <?php 
            if($_SESSION["finished"] > -1){
                
                $_SESSION["points"][$_SESSION["symbol"]]++;
                 
                $top = 0; 
                $left = 0; 
                $rotation = 0;
                $width = 500;  

                if($_SESSION["finished"] < 6){
                    if($_SESSION["finished"] < 3){
                        $top = 30 + 170 * $_SESSION["finished"]; 
                    }else{
                        $rotation = 90;
                        $top = 195; 
                        if($_SESSION["finished"] != 4){
                            $left = ($_SESSION["finished"] < 4 ? -170 : 170); 
                        }
                    }
                }else{
                    $width = 620; 
                    $top = 200; 
                    $left = -50; 
                    $rotation = $_SESSION["finished"] % 2 ? -45 : 45; 
                }

                echo '#winningSlab{
                    display:block;
                    width:'. $width .'px;
                    top:' .$top. 'px;
                    left:'.$left.'px;
                    transform: rotate('.$rotation.'deg);
                }';

            }
        ?>
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