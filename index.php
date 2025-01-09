<?php
include("config.php");

require('security_header.php');
require('security_user.php');

$page = isset($_GET['page']) ? $_GET['page'] : 'home';

if ($page === 'login') {
    echo "Avant inclusion du login.php"; // Débogage
    include("login.php");
    echo "Après inclusion du login.php"; // Débogage
} elseif ($page === 'books') {
    include("books.php");
} else {
    include("home.php");
}

include("footer.php");
?>
