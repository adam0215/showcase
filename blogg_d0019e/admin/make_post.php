<?php
require_once '../db/db_query.php';
require_once '../utils/session_checker.php';

// Funktion för att ladda upp en bild
function upload_image()
{
    // Hämta den uppladdade filen 
    $file = $_FILES['post-cover'];

    // Titta om filen laddades upp utan fel
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Hämta filnamn och filändelse
        $filename = basename($file['name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Generera ett unikt filnamn för att undvika att skriva över existerande bilder
        $unique_filename = uniqid() . ".$ext";

        // Flytta den uppladdade filen till "uploads"-mappen
        move_uploaded_file($file['tmp_name'], "../uploads/$unique_filename");
        return $unique_filename;
    } else {
        // Vid eventuellt fel
        return null;
    }
}

function create_blogpost($title, $content, $author, $image_name, $image_desc)
{
    $db_query = new DatabaseQuery;
    // Lägg till bilddata i "image"-tabellen
    $image_db_id = $db_query->add_image($image_name, $image_desc);
    $post_id = $db_query->create_post($title, $content, $author, $image_db_id);
    return $post_id;
}

if (isset($_POST['post-title']) && isset($_POST['post-content'])) {
    $post_title = $_POST['post-title'];
    $post_content = $_POST['post-content'];
    $post_img = upload_image() ?? '';
    $post_img_desc = $_POST['cover-description'] ?? '';
    $author_id = $_SESSION['id'];

    // Skapa post i databas och lägg inläggets id i en variabel
    $post_id = create_blogpost($post_title, $post_content, $author_id, $post_img, $post_img_desc);
    // Dirigera om användaren till inlägget när inlägget är skapat
    header("Location: ../blog_post.php?id=$post_id&np=true");
}

?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skapa inlägg</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="../javascript/make_post.js" defer></script>
</head>

<body>

    <?php include_once '../components/header.php' ?>

    <main class="main-container post-main">

        <form id="post-form" class="post-form" method="POST" enctype="multipart/form-data">

            <div class="post-input-container">
                <figure class="post-cover-container">
                    <label class="hidden-label" for="post-title">Omslag</label>
                    <div id="img-selector-container" class="styled-img-selector post-cover-selector">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <input type="file" id="post-cover" name="post-cover" accept="image/jpeg, image/png, image/jpg" required>
                    </div>

                    <input id="cover-description" name="cover-description" type="text" autocomplete="off" maxlength="120" placeholder="Beskriv din bild här och gör upplevelsen bättre för alla..." class="post-cover-text-input">

                </figure>

                <div class="post-title-container">
                    <label for="post-title">Titel</label>
                    <textarea class="post-title-input text-area-util" id="post-title" name="post-title" placeholder="Döp ditt inlägg..." required></textarea>
                </div>

                <div class="post-content-container">
                    <label for="post-content">Innehåll</label>
                    <textarea id="post-content" class="post-content-input text-area-util" name="post-content" placeholder="Skriv något roligt här..."></textarea>
                </div>
            </div>

            <div class="bottom-screen-btn-bar">
                <a class="secondary-btn floating-btn" href=<?= isset($_GET['location']) ? $_GET['location'] : '/' ?>>Avbryt</a>
                <input class="primary-btn floating-btn" type="submit" value="Skapa inlägg" name="create-post">
            </div>
        </form>
    </main>

</body>

</html>