
<?php

if (isset($_POST['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: http://localhost/Blog/front-end/user/login.html");
    exit();
}

?>