<?php
require_once 'db/db_query.php';

$db_query = new DatabaseQuery;
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alla bloggare</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>
    <?php include_once 'components/header.php' ?>

    <section id="our-bloggers-section" class="start-section our-authors-section all-authors">
        <h1>Alla våra kreatörer</h1>
        <div>

            <?php
            // Hämta senaste 100 kretörerna
            $creator_list = $db_query->get_creator_list(100);
            $creator_list = mysqli_fetch_all($creator_list, MYSQLI_ASSOC);

            // Rendera lista med kretörer
            foreach ($creator_list as $index => $creator) :
                $creator_firstname = htmlentities($creator['firstname']);
                $creator_lastname = htmlentities($creator['lastname']);
                $creator_username = htmlentities($creator['username']);
                $creator_profile_pic = htmlentities($creator['image_filename']);
                // SQL-raderna är sorterade efter nyast användare först. Titta efter index 0 och ge det element klassen "newest-author-card"
                $newest_author_class = $index === 0 ? 'newest-author-card' : '';
            ?>
                <a href="creator.php?creator=<?= $creator_username ?>" class="invisible-link">
                    <div class="card-container card-hover md-user-card-container <?= $newest_author_class ?>">
                        <img src=<?= "uploads/$creator_profile_pic" ?> alt="<?= $creator['image_description'] ?>" class="profile-pic">
                        <div class="md-user-card-info">
                            <h5><?= "$creator_firstname $creator_lastname" ?></h5>
                            <span><?= $creator_username ?></span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>
    </section>

    <?php include_once 'components/footer.php' ?>
</body>

</html>