<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['errors'])) {
    $_SESSION['errors'] = [];
}

header("X-Frame-Options: SAMEORIGIN"); // protection iframe
header("X-Content-Type-Options: nosniff"); //éviter le "content sniffing".