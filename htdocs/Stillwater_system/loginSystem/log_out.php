<?php
session_start();

include "../db_connection.php";

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page
echo"<script>window.history.back()</script>";
exit();
?>