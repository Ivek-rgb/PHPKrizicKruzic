<?php 
    
    function returnReturnForm(string $redirectFileName){
        return '
        <form method="POST" action="' . $redirectFileName . '" class="returnForm">
            <input type="submit" id="return" name="return" value="Return"> 
        </form>
        ';
    }

    if(isset($_POST["return"]) && $_POST["return"] != ""){

        session_unset(); 
        session_destroy(); 

        header("Location: index.php");
    }

?>