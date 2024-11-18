<?php
session_start();

// Vernietig alle sessiegegevens
session_unset();
session_destroy();

// Stuur de gebruiker terug naar de loginpagina
header('Location: welcome.php');
exit;
?>
