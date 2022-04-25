<?php
    session_start();
    require("./php/postmanager.php");
    require("./php/functions/users_functions.php");

    //Vymazat pamět stránky, pokud se uživatel odhlásil
    if (isset($_GET["logout"])) {
        Logout();
    }
    $query = "SELECT * FROM posts P INNER JOIN users U ON P.user_owner = U.id_u ORDER BY P.id_p DESC LIMIT 2";

    require("./phtml/_index.phtml");
?>
