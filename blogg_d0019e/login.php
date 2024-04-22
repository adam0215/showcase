<?php
require_once 'db/db_query.php';
require_once 'utils/auth_utils.php';

// Omdirigera användaren ifall de redan har en aktiv session / Är inloggade
session_start();
if (isset($_SESSION['id'])) {
    header('Location: ./index.php');
    die();
}


// Variabel som innehåller eventuella fel som sedan skrivs ut vid respektive HTML-element
$inputError = ['passwordError' => '', 'usernameError' => ''];

// Om användarnamn och lösen är ifyllt
if (isset($_POST['username']) && isset($_POST['password'])) {
    // Lägg in de ifyllda uppgifterna i variabler, om någon av dem inte finns avsluta skriptet med ett felmeddelande
    $inputUsername = $_POST['username'] ?? exit('Användarnamn tomt!');
    $inputPassword = $_POST['password'] ?? exit('Lösenord tomt!');
    // Sanera användarinput för att motverka olika typer av skadliga attacker som involverar körandet av obehörig kod.
    $inputUsername = htmlentities($inputUsername);
    $inputPassword = htmlentities($inputPassword);
    // Instansiera klass med metoder och data kopplat till användardata
    $userHandler = new UserHandler();

    // Klickade "Logga in"
    if (isset($_POST['log-in'])) {
        // Verifiera användaren
        $user_verification = $userHandler->verify_user($inputUsername, $inputPassword);
        if ($user_verification['status']) {
            // Om användaren finns, skapa en session och vidarebefordra till en annan sida
            initiate_session($user_verification['user_data']);

            // Om det inte finns någon "location"-parameter i urlen, skicka till index, annars skicka till sidan som specificeras i "location"
            if (!isset($_GET['location'])) {
                header("Location: ./index.php");
                die();
            }
            $url_location_param = htmlentities($_GET['location']);
            header("Location: $url_location_param");
            // Terminera skriptet
            exit();
            // Om användaren inte kan verifieras skicka lösenordsfel och avsluta funktionen / Logga inte in
        } else $inputError['passwordError'] = 'Lösenordet är felaktigt.';
    }
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logga in</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

    <?php include_once 'components/header.php' ?>

    <main class="main-container auth-col-2">
        <div class="col">
            <h1>Logga in</h1>
            <p>Excepteur velit ad adipisicing ullamco fugiat dolor culpa do magna irure. Qui commodo consequat incididunt voluptate minim pariatur laboris tempor eu nulla. Anim sunt incididunt eiusmod. Anim laborum do nisi. Qui mollit nostrud ullamco. Laboris velit id ipsum esse anim commodo excepteur in. Est nisi veniam deserunt occaecat incididunt laborum. Non deserunt aliqua cillum proident labore esse dolore adipisicing.</p>
        </div>

        <div class="col">
            <form class="form-container" method="POST">

                <div class="form-input-container">
                    <div class="label-input-container">
                        <label for="username">Användarnamn</label>
                        <input type="text" id="username" name="username" placeholder="Användarnamn" pattern="^[a-zA-Z0-9]+$" maxlength="16" required>
                        <?= generate_error('username') ?>
                    </div>

                    <div class="label-input-container">
                        <label for="password">Lösenord</label>
                        <input type="password" id="password" name="password" placeholder="••••••••••" minlength="6" maxlength="40" required>
                        <?= generate_error('password') ?>
                    </div>

                </div>
                <div class="form-button-container">
                    <input class="primary-btn" type="submit" value="Logga in" name="log-in">
                    <a href="register.php">Inget konto? Skapa ett här!</a>
                </div>
            </form>

        </div>
    </main>

</body>

</html>