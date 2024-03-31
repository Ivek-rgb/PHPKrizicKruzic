<?php 

    function resetCounters(){
        $_SESSION["addition"] = [0,0,0,0,0,0,0,0]; 
        $_SESSION["finished"] = -1;
    }

    function resetBoard(){
        $_SESSION["cells"] = ["", "", "", "","","","","",""];  
        $_SESSION["symbol"] = null;
    }

    function addToMarkedPositions($markValue, $position, &$additionArray){
        
        $additionArray[intval($position / 3)] += $markValue; 
        if($additionArray[intval($position / 3)] / 3 == $markValue) return intval($position / 3); 
        
        $additionArray[$position % 3 + 3] += $markValue; 
        if($additionArray[$position % 3 + 3] / 3 == $markValue) return $position % 3 + 3; 
        
        if($position % 2 == 0){
             
            if($position % 4 == 0){
                $additionArray[6] += $markValue;
                if($additionArray[6] / 3 == $markValue) return 6; 
            } 
            else $additionArray[7] += $markValue; 
            if($position == 4) $additionArray[7] += $markValue; 

            if($additionArray[7] / 3 == $markValue) return 7;
        }

        return -1; 

    }

    function checkForDisabled(int $idxBtn){
        if(isset($_SESSION["cells"])){
            if($_SESSION["cells"][$idxBtn] != "" || $_SESSION["finished"] != -1) return "disabled"; 
        }
        return ""; 
    }

?>