<!--<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
    <link type="text/css" rel="stylesheet" href="css/stil.css"/>
    <link rel="stylesheet" type="text/css" href="css/grid.css"/>
</head>
<body>
    <header>
        <div class="row">
            <h1>Logo</h1>
            <ul>
                <li><a href="#">Prijavi se</a></li>
                <li>
                    Nisi još korisnik, a želiš prijaviti svoju tvrtku?<br>
                    <a href="#">Registriraj se</a>
                </li>
            </ul>
        </div>
    </header>

    <div class="row">


    </div>

    <footer>
        <div class="row">
            <h1>Disclaimer</h1>
            <p>Copyright©DWAbla</p>
        </div>
    </footer>
</body>

</html>-->
<?php include_once("site parts/header.php"); ?>

<div class="row">
    <div class="column column-12">
        <form>
            <label>Filtriraj sadržaj: </label>
            <label for="tip" style="padding-left:1.8%">Tip trgovine:</label>
            <select name="tip" id="tip">
                <option value="pekara">Pekara</option>
                <option value="opca">Opća</option>
                <option value="mesinca">Mesnica</option>
                <option value="vocarna">Voćarna</option>
            </select>
            <input type="checkbox" name="free" id="free"/>
            <label for="free">Prikaži samo besplatno</label>
        </form>
        <div id="map"></div>
    </div>
</div>

<?php include_once("site parts/footer.php"); ?>