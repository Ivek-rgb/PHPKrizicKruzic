<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./index.css">
    <title>Križić Kružić - Main page</title>
</head>

<body>
    <h1>Križić Kružić - Selection page</h1>
    <div class="selectionBox">
        <h2>Singleplayer - protiv bota</h2>
        <form method="POST" action="singleplayer.php">
            Izaberi ikonu:<br>
            <input type="radio" name="playerSymbol" value="cross" checked="checked">X<br>
            <input type="radio" name="playerSymbol" value="circle">O<br>
            <input type="checkbox" name="randomPos" >
            Nasumična startna pozicija bota<br><br>
            <input type="submit" class="buttons"  name="startGame" value="Start SP">
        </form>
    </div>

    <div class="selectionBox">
        <h2>Multiplayer - naizmjence dva igrača</h2>
        <form method="POST" action="multiplayer.php">
        <input type="submit" class="buttons"  name="startGame" value="Start MP">
        </form>
    </div>

</body>
</html>