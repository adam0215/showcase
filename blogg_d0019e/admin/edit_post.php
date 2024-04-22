<?php
require_once '../db/db_query.php';
require_once '../utils/session_checker.php';

$db_query = new DatabaseQuery;

// Hämta data om inlägget som ska uppdateras
if (isset($_GET['id'])) {
    $post_to_edit = $_GET['id'];
    $post_data = $db_query->get_post($post_to_edit);

    $post_author = $post_data['author'];
    $signed_in_user = $_SESSION['id'];

    // Om användaren inte äger inlägget, döda skriptet
    if ($post_author !== $signed_in_user) {
        die('Du har inte tillgång till detta inlägg!');
    }

    $post_title = $post_data['title'];
    $post_content = $post_data['content'];
    $post_img = $post_data['image_filename'];
    $post_img_id = $post_data['image_id'];

    // Om en bild finns
    if ($post_img !== '') {
        // Generera checksum för befintlig bild bunden till inlägget för senare jämförelse med ev. ny bild
        $post_img_checksum = md5_file("../uploads/$post_img");
    }
}

// Uppdatera inlägg
if (isset($_POST['post-title']) && isset($_POST['post-content'])) {
    $new_post_title = $_POST['post-title'];
    $new_post_content = $_POST['post-content'];
    $new_post_img = upload_updated_image();
    $new_post_img_desc = $_POST['cover-description'] ?? '';
    $author_id = $_SESSION['id'];

    // Skapa post i databas och lägg inläggets id i en variabel
    update_blogpost($post_to_edit, $new_post_title, $new_post_content, $new_post_img, $new_post_img_desc);
    // Dirigera om användaren till inlägget när inlägget är skapat
    header("Location: ../blog_post.php?id=$post_to_edit&up=true");
}

function upload_updated_image()
{
    // Hämta den uppladdade filen och lägg i variabel
    $file = $_FILES['post-cover'];

    // Titta om filen laddades upp utan fel
    if ($file['error'] === UPLOAD_ERR_OK) {

        // Generera checksum och jämför för att se om användaren laddade upp samma bild basereat på dess binära representation.
        $uploaded_img_checksum = md5_file($file['tmp_name']);
        // Hämta global variabel för att komma åt checksum för bilden som redan finns
        global $post_img_checksum;

        /*
        .
        .
        .
        . 
        */
        // Om den uppladdade bilden är exakt samma som den som redan är bunden till inlägget, ladda inte upp nån ny bild
        if ($post_img_checksum === $uploaded_img_checksum) {
            return null;
        }
        /*
        .
        .
        .
        . 
        */

        // Hämta filnamn och filändelse
        $filename = basename($file['name']);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        // Generera nytt unikt id som filnamn för att undvika att filer med samma namn skriver över varandra
        $unique_filename = uniqid() . ".$ext";

        // Flytta uppladdad fil till /uploads
        move_uploaded_file($file['tmp_name'], "../uploads/$unique_filename");
        return $unique_filename;
    } else {
        // Vid eventuellt fel
        return null;
    }
}

function update_blogpost($id, $title, $content, $image_name, $image_desc)
{
    global $db_query;
    global $post_img_id;
    $image_db_id = null;

    // Om $image_name inte är null, dvs om användaren faktiskt uppdaterat bilden
    if ($image_name) {
        // Lägg till bildinfo i "image"-tabellen
        $image_db_id = $db_query->add_image($image_name, $image_desc);
    } else {
        $db_query->update_image_description($post_img_id, $image_desc);
    }

    $db_query->update_post($id, $title, $content, $image_db_id);
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
    <!-- DELAR JS MED "MAKE_POST.PHP" -->
    <script src="../javascript/make_post.js" defer></script>
</head>

<body>

    <?php include_once '../components/header.php' ?>

    <main class="main-container post-main">

        <form id="post-form" class="post-form" method="POST" enctype="multipart/form-data">

            <div class="post-input-container">

                <figure class="post-cover-container">
                    <label class="hidden-label" for="post-title">Omslag</label>
                    <div id="img-selector-container" class="styled-img-selector post-cover-selector" data-coverid="<?= $post_img ?>">
                        <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <input type="file" id="post-cover" name="post-cover" accept="image/jpeg, image/png, image/jpg">
                    </div>

                    <input id="cover-description" name="cover-description" type="text" autocomplete="off" maxlength="120" placeholder="Beskriv din bild här och gör upplevelsen bättre för alla..." class="post-cover-text-input">

                </figure>

                <div class="post-title-container">
                    <label for="post-title">Titel</label>
                    <textarea class="post-title-input text-area-util" id="post-title" name="post-title" placeholder="Lorem ipsum"><?= $post_title ?></textarea>
                </div>

                <div class="post-content-container">
                    <label for="post-content">Innehåll</label>
                    <textarea id="post-content" class="post-content-input text-area-util" name="post-content" placeholder="Skriv något roligt här..."><?= $post_content ?></textarea>
                </div>
            </div>

            <div class="bottom-screen-btn-bar">
                <a class="secondary-btn floating-btn" href="./index.php">Avbryt</a>
                <input class="primary-btn floating-btn" type="submit" value="Uppdatera inlägg" name="create-post">
            </div>
        </form>
    </main>

</body>

</html>