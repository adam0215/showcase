<?php
require_once '../utils/session_checker.php';
require_once '../db/db_query.php';

$db_query = new DatabaseQuery;

$user_id = $_SESSION['id'];
$user_firstname = htmlentities($_SESSION['firstname']);
$user_lastname = htmlentities($_SESSION['lastname']);
$user_profile_picture = htmlentities($db_query->get_image_filename($_SESSION['profile_picture']));
$user_bio = htmlentities($_SESSION['biography'] ?? '');

// Uppdatera bio
if (isset($_POST['biography'])) {
    $new_bio = $_POST['biography'];
    // Begränsa bion till 240 tecken, som uttryckt i gränssnittet
    $new_bio_limited = substr($new_bio, 0, 240);

    // Uppdatera bion i databasen
    $db_query->update_creator_bio($user_id, $new_bio_limited);

    // Hämta nya bion från databasen
    $fetch_bio = $db_query->get_creator_bio($user_id);
    // Uppdatera bion i sessionen
    $_SESSION['biography'] = $fetch_bio;
    // Uppdatera sidan för att förändringen ska synas
    header("Refresh:0");
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="../javascript/index_admin.js" defer></script>
</head>

<body>

    <?php include_once '../components/header.php' ?>

    <dialog id="post-deletion-dialog" class="dialog-container">
        <div>
            <h3>Ta bort inlägg</h3>
            <p>Vill du ta bort inlägget: <em id="dialog-post-title-preview">"Ex tempor id officia proident esse occaecat dolore."</em> ?</p>
        </div>
        <form method="dialog" onsubmit="deletePost()">
            <div>
                <button id="close-dialog-btn" type="reset" class="secondary-btn">Avbryt</button>
                <button id="submit-dialog-btn" type="submit" class="primary-btn">Ta bort</button>
            </div>
        </form>
    </dialog>

    <main class="main-container admin-main">

        <aside class="admin-sidebar">
            <div class="admin-profile-info">
                <img class="profile-pic" src="../uploads/<?= $user_profile_picture ?>" alt="">
                <h2><?= $user_firstname . ' ' . $user_lastname ?></h2>
            </div>

            <form class="admin-bio-container" id="bio-form" name="bio-form" method="POST">
                <div class="bio-character-counter-container">
                    <label for="biography">Bio</label>
                    <span id="bio-character-counter">0/240</span>
                </div>
                <textarea class="text-area-util" name="biography" id="biography" placeholder="Vem är du?"><?= $user_bio ?></textarea>
                <button id="save-bio-btn" class="primary-btn d-none">Spara bio</button>
            </form>
        </aside>


        <div class="admin-center-area">
            <h1>Välkommen tillbaka <?= $user_firstname ?></h1>
            <div class="admin-post-container">
                <h2>Dina inlägg</h2>
                <div class="admin-post-list">

                    <!-- RENDERA ALLA ANVÄNDARENS INLÄGG -->
                    <?php
                    $all_user_posts = $db_query->get_all_posts_from_creator($user_id);

                    // Rendera inte listan över alla inlägg ifall användaren inte har några
                    if ($all_user_posts === null) {
                        echo "<p>Du har inte skapat några inlägg</p>";
                        exit();
                    };

                    // Rendera alla användarens inlägg
                    foreach ($all_user_posts as $post) :
                        $post_id = htmlentities($post['id']);
                        $post_title = htmlentities($post['title']);
                        $post_content = htmlentities($post['content']);
                        $post_content_substr = htmlentities(substr($post['content'], 0, 320));
                        $post_image = htmlentities($db_query->get_image_filename($post['image']));
                    ?>

                        <div class="post-list-item-controls">

                            <a class="invisible-link" href=" <?= "../blog_post.php?id=$post_id" ?>">
                                <div class="post-list-item">
                                    <div>
                                        <h3><?= $post_title ?></h3>
                                        <p><?= $post_content_substr ?></p>
                                    </div>
                                    <div>
                                        <img src="../uploads/<?= $post_image ?>" alt="">
                                    </div>
                                </div>
                            </a>

                            <!-- INLÄGGSKONTROLLER -->
                            <div data-postid="<?= $post_id ?>" data-posttitle="<?= $post_title ?>">
                                <!-- Redigera knapp -->
                                <a href="./edit_post.php?id=<?= $post_id ?>" title="Redigera inlägg" class="icon-btn primary-btn edit-post-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                                <!-- Ta bort knapp -->
                                <button title="Ta bort inlägg" class="icon-btn primary-btn remove-post-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </main>

    <?php include_once '../components/footer.php' ?>

</body>

</html>