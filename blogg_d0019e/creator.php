<?php
require_once 'db/db_query.php';

$db_query = new DatabaseQuery;

if (isset($_GET['creator'])) {
    // Den valda kreatören i urlen
    $param_username = $_GET['creator'];

    // Hämta kreatördata baserat på användarnamn
    $creator_data = $db_query->get_creator(null, $param_username)[0];

    $creator_firstname = $creator_data['firstname'];
    $creator_lastname = $creator_data['lastname'];
    $creator_username = $creator_data['username'];
    $creator_description = $creator_data['biography'];
    $creator_image_data = $db_query->get_image_data($creator_data['profile_picture'])[0];
} else {
    // Om ingen kreatör har matats in i urlen som "creator"-parameter, dirigera användaren till framsidan
    header('Location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adam Gustafsson</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <?php include_once 'components/header.php' ?>

    <main class="main-container">
        <div class="creator-profile-info-container">
            <img class="profile-pic" src="uploads/<?= $creator_image_data['filename'] ?>" alt="<?= $creator_image_data['description'] === '' ? 'Användares profilbild' : $creator_image_data['description'] ?>">
            <div>
                <div>
                    <h1><?= "$creator_firstname $creator_lastname" ?></h1>
                    <span><?= $creator_username ?></span>
                </div>
                <p><?= $creator_description ?></p>
            </div>
        </div>

        <div class="post-list">
            <!-- INLÄGGSLISTA -->
            <?php
            // Hämta alla kreatörens inlägg
            $all_posts_data = $db_query->get_latest_from_creator($creator_data['id'], 100);

            // Om användaren inte gjort några inlägg
            if ($all_posts_data === null) {
                echo "<p>Användaren har inte gjort några inlägg.</p>";
                exit();
            }

            $all_posts_data = mysqli_fetch_all($all_posts_data, MYSQLI_ASSOC);

            // Rendera inlägg
            foreach ($all_posts_data as $post) :
                $post_id = $post['id'];
                $post_title = $post['title'];
                $post_content = $post['content'];
                // Begränsa antalet ord som skickas med i förhandsvisningen för att inte lägga in hela artikeln i förhandsvisningen (Textlängden trunkeras också genom CSS)
                $post_content_limited = substr($post_content, 0, 300);
                $post_img = $db_query->get_image_filename($post['image']);
                // Dela upp textsträngen för att enbart visa datum, inte tid
                $post_publish_date = $post['created_at'];
                // Ungefärligt antal ord för estimering av ungefärlig lästid
                $post_word_count = count(explode(' ', $post_content));
                // Ungefärlig lästid (i minuter) av artikel baserat på en genomsnittlig läshatighet på 250 ord/min
                $post_est_reading_t = strval(round($post_word_count / 250));
            ?>
                <a class="invisible-link" href="<?= "blog_post.php?id=$post_id" ?>">
                    <div class="post-list-item">
                        <div>
                            <h3><?= $post_title ?></h3>
                            <span><?= "$post_publish_date | ~ $post_est_reading_t min läsning" ?></span>
                            <p><?= $post_content_limited ?></p>
                        </div>
                        <div>
                            <img src=<?= "uploads/$post_img" ?> alt="">
                        </div>
                    </div>
                </a>

            <?php endforeach; ?>

        </div>
    </main>

    <?php include_once 'components/footer.php' ?>
</body>

</html>