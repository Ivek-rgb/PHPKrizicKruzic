<?php 
            if($_SESSION["finished"] != -1){
                
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