<?php
// Página para logout do usuário
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit();
?> 