<?php
require_once 'db/db_query.php';
require_once 'utils/auth_utils.php';

// Omdirigera användaren ifall de redan har en aktiv session / Är inloggade
session_start();
if (isset($_SESSION['id'])) {
    header("Location: ./index.php");
    die();
}

// Variabel som innehåller eventuella fel som sedan skrivs ut vid respektive HTML-element
$inputError = ['passwordError' => '', 'usernameError' => ''];

// Om en POST-förfrågan görs
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lägg in de ifyllda uppgifterna i variabler, om någon av dem inte finns avsluta skriptet med ett felmeddelande
    $inputFirstname = $_POST['firstname'] ?? exit('Förnamn tomt!');
    $inputLastname = $_POST['lastname'] ?? exit('Efternamn tomt!');
    $inputUsername = $_POST['username'] ?? exit('Användarnamn tomt!');
    $inputPassword = $_POST['password'] ?? exit('Lösenord tomt!');
    // Sanera användarinput för att motverka olika typer av skadliga attacker som involverar körandet av obehörig kod.
    $inputFirstname = htmlentities($inputFirstname);
    $inputLastname = htmlentities($inputLastname);
    $inputUsername = htmlentities($inputUsername);
    $inputPassword = htmlentities($inputPassword);
    // Instansiera klass med metoder och data kopplat till användardata
    $userHandler = new UserHandler();

    // Klickade "Skapa ny användare"
    if (isset($_POST['new-user'])) {
        // Sök efter användarnamnet i textfilen
        if ($userHandler->user_exists($inputUsername)['status']) {
            // Om användarnamnet finns, stanna och varna
            $inputError['usernameError'] = 'Användarnamnet är upptaget.';
        } else {
            // Skapa användare
            $userHandler->add_user($inputFirstname, $inputLastname, $inputUsername, $inputPassword);
            // Ändra variabeln för att visa success-meddelande
            $userCreatedSucess = true;
        }
    }
}

function upload_image()
{
    // Hämta den uppladdade filen
    $file = $_FILES['profile-pic'];

    // Titta om den laddades upp utan fel
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Hämta filnamnet och filändelsen
        $filename = basename($file['name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Generera ett nytt unik filnamn för att motverka att skriva över existerande bilder
        $unique_filename = uniqid() . ".$ext";

        // Flytta bilden till "uploads"-mappen
        move_uploaded_file($file['tmp_name'], "uploads/$unique_filename");
        return $unique_filename;
    } else {
        // Vid eventuellt fel
        return null;
    }
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrera dig</title>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="javascript/register.js" defer></script>
</head>

<body>

    <?php include_once 'components/header.php' ?>

    <main class="main-container auth-col-2">

        <div class="col">
            <h1>Registrera dig</h1>
            <p>Excepteur velit ad adipisicing ullamco fugiat dolor culpa do magna irure. Qui commodo consequat incididunt voluptate minim pariatur laboris tempor eu nulla. Anim sunt incididunt eiusmod. Anim laborum do nisi. Qui mollit nostrud ullamco. Laboris velit id ipsum esse anim commodo excepteur in. Est nisi veniam deserunt occaecat incididunt laborum. Non deserunt aliqua cillum proident labore esse dolore adipisicing.</p>
        </div>

        <div class="col">
            <form class="form-container" method="POST" enctype="multipart/form-data">

                <div class="form-input-container">
                    <div id="img-selector-container" class="styled-img-selector profile-pic-selector">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <label class="hidden-label" for="profile-pic">Profilbild</label>
                        <input type="file" id="profile-pic" name="profile-pic" required>
                    </div>
                    <div class="label-input-container">
                        <label for="firstname">Förnamn</label>
                        <input type="text" id="firstname" name="firstname" placeholder="John" required>
                    </div>

                    <div class="label-input-container">
                        <label for="lastname">Efternamn</label>
                        <input type="text" id="lastname" name="lastname" placeholder="Doe" required>
                    </div>

                    <div class="label-input-container">
                        <label for="username">Användarnamn</label>
                        <input type="text" id="username" name="username" placeholder="johndoe" pattern="^[a-zA-Z0-9]+$" maxlength="16" required>
                        <?= generate_error('username') ?>
                    </div>

                    <div class="label-input-container">
                        <label for="password">Lösenord</label>
                        <input class="input-error" type="password" id="password" name="password" placeholder="••••••••••" minlength="6" maxlength="40" required>
                        <?= generate_error('password') ?>
                    </div>

                </div>
                <div class="form-button-container">
                    <input class="primary-btn" type="submit" value="Registrera dig" name="new-user">
                    <a href="./login.php">Har du redan ett konto? Logga in här!</a>
                </div>
            </form>
        </div>
    </main>

</body>

</html>