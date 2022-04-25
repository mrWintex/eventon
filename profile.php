<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index-style.css">
    <link rel="stylesheet" href="css/post-style.css">
    <title>Document</title>
</head>
<body>
    <?php
        session_start();
        $myPosts_query = "SELECT * FROM prispevky P INNER JOIN uzivatele U ON P.uzivatel = U.uid WHERE uzivatel = {$_SESSION["user"]["uid"]}";
    ?>

    <button onclick="LoadPosts('<?= $myPosts_query ?>')">Moje příspěvky</button>
    <button>Oblíbené příspěvky</button>

    <div id="posts-container"></div>
    <div id="loader_image" style="visibility:hidden;width:50px;margin:50px auto"><img src="images/LoadingGif.gif" style="width:50px;height:50px"alt=""></div>


    <script type="text/javascript">
        function LoadPosts(php_query){
            console.log("started!");
            var xmlhttp_request = new XMLHttpRequest();
            xmlhttp_request.onreadystatechange = function() {
                if(this.readyState == 4 && this.status == 200){
                    console.log("ended!");
                    document.getElementById("posts-container").innerHTML = this.responseText;
                }
            };
            xmlhttp_request.open("GET", "postmanager.php?query="+php_query, true);
            xmlhttp_request.send();
        }
    </script>
</body>
</html>