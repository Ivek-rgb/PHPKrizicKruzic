<?php

    define("points", ["circle" => 50, "cross" => 1]);
    session_start(); 

    if(isset($_POST["playerSymbol"])){
        $_SESSION["playerSymbol"] = $_POST["playerSymbol"]; 
    }

    define("player", $_SESSION["playerSymbol"]); 
    define("aiPlayer", $_SESSION["playerSymbol"] == "cross" ? "circle" : "cross"); 
    
    require "./board_functions.php"; 

    if(!isset($_SESSION["cells"])){
        $_SESSION["cells"] = ["", "", "", "", "", "", "", "", ""]; 
        $_SESSION["points"] = ["circle" => 0, "cross" => 0];
        $_SESSION["moves"] = 0; 
        $_SESSION["random"] = $_POST["randomPos"] ?? "" != ""; 
        resetCounters(); 
    }

    function returnMostOptimisticPosition(){

        $moveArray = array(); 
        for($i =0; $i < 9; $i++){
            
            $additionArrayCopy = $_SESSION["addition"];
            $boardCopy = $_SESSION["cells"];  
            
            if($boardCopy[$i]==""){
                
                $returnedAtAddition = addToMarkedPositions(points[aiPlayer], $i, $additionArrayCopy);
                
                if($returnedAtAddition != -1){
                    return $i; 
                }
                
                $moveArray[$i] = branchAndMinMaxOptions($boardCopy, $additionArrayCopy, 1); 

            }
        }

        arsort($moveArray);

        $returnPossibilities = []; 
        $currValue = 0; 

        foreach($moveArray as $key => $value){
            if(count($returnPossibilities) == 0 || $value == $currValue){
                array_push($returnPossibilities, $key);
                $currValue = $value; 
            }else break; 
        }

        if(count($returnPossibilities) == 0)
            return null;
        else return $returnPossibilities[rand(0, count($returnPossibilities) - 1)]; 

    }


    function branchAndMinMaxOptions($boardStatus, $additionArray, $isBotOnMove){
        
        $symbol = (!($isBotOnMove % 2)) ? aiPlayer : player;
        $returnResult = 0; $bestPoints = NULL; 
        
        for($i = 0; $i < 9; $i++){

            $boardCopy = $boardStatus; 
            $additionArrayCopy = $additionArray;

            if($boardCopy[$i] == ""){
                
                $boardCopy[$i] = $symbol; 
                $returnedAtAddition = addToMarkedPositions(points[$symbol], $i, $additionArrayCopy); 

                if($returnedAtAddition != -1 && !($isBotOnMove % 2)){
                    return  15 - $isBotOnMove;  
                }else if($returnedAtAddition != -1){
                    return - 15 - (8 - $isBotOnMove)**2; 
                }else if($_SESSION["moves"] + $isBotOnMove >= 8){
                    return 0; 
                }

                $returnResult = branchAndMinMaxOptions($boardCopy, $additionArrayCopy, $isBotOnMove + 1);
                
                if(($isBotOnMove % 2) && (!isset($bestPoints) || $returnResult < $bestPoints )){
                    $bestPoints = $returnResult; 
                }else if(!isset($bestPoints) || ($returnResult > $bestPoints)){
                    $bestPoints = $returnResult;
                }

            }
        }

        return $bestPoints; 
    }

    function markForAi(int $idxBtn){
        $_SESSION["cells"][$idxBtn] = aiPlayer; 
        return $_SESSION["cells"][$idxBtn]; 
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        if($_POST["resetBtn"] ?? "None" == "Reset"){
            resetCounters(); 
            resetBoard(); 
            $_SESSION["points"]["circle"] = 0;
            $_SESSION["points"]["cross"] = 0;
            $_SESSION["moves"] = 0;
        } 

        if($_POST["continueBtn"] ?? "None" == "Continue"){
            resetCounters();
            resetBoard(); 
            $_SESSION["moves"] = 0;
        }

        if(isset($_POST["cell"])){
            $_SESSION["finished"] = addToMarkedPositions(points[player], $_POST["cell"], $_SESSION["addition"]);
            $_SESSION["last"] = player; 
            $_SESSION["cells"][$_POST["cell"]] = player;
            $_SESSION["moves"]++;
            if($_SESSION["moves"] > 8){
                $_SESSION["finished"] = -2; 
            }
        }

    }

    if(aiPlayer == "cross" || $_SESSION["moves"] > 0){
        $bestMove = -1; 
        if($_SESSION["random"] && !$_SESSION["moves"]){
            $bestMove = rand(0, 8);
        }else $bestMove = returnMostOptimisticPosition();  
        if($_SESSION["finished"] == -1){
            $_SESSION["last"] = aiPlayer;
            $_SESSION["moves"]++; 
            $_SESSION["finished"] = addToMarkedPositions(points[aiPlayer], $bestMove, $_SESSION["addition"]); 
            markForAi($bestMove);
        }
        if($_SESSION["moves"] > 8 && $_SESSION["finished"] == -1){
            $_SESSION["finished"] = -2; 
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
    <title>Krizic kruzic SP</title>
    <style>
        <?php 
            if($_SESSION["finished"] > -1){
                
                $_SESSION["points"][$_SESSION["last"]]++;
                 
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
        <h1>PHP Križić kružić</h1>
        <h2><?php 
            echo "O:{$_SESSION["points"]["circle"]}\nX:{$_SESSION["points"]["cross"]}"; 
        ?></h2>
        <form method="POST" action="singleplayer.php" class="playingForm">
            <?php require "./game_grid.php"; ?>
        </form>
        <?php 
            require "./modular_return.php"; 
            echo returnReturnForm("singleplayer.php");        
        ?>
    </div>
</body>
</html>