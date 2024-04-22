<?php
// Starta en session för att kunna hitta sessionen som startades när användaren loggade in
session_start();
// Om sessionsvariabeln "username" inte är satt, skicka tillbaka användaren till login.php och terminera skriptet
if (!isset($_SESSION['username'])) {
    // Dirigera om användaren till logga in och döda skriptet. Skicka med vilken sida de kom ifrån i urlen så att "logga in"-sidan vet var den ska skicka tillbaka användaren
    header("Location: ../login.php?location=" . urlencode($_SERVER['REQUEST_URI']));
    die();
}
