<?php
session_start();session_unset();session_destroy();
header("Location: index.php?error=Logged%20Out!");
exit;
