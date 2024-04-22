<?php
require_once 'db/db_query.php';

// Svenska månader
define('MONTHS_SV', [
    'januari',
    'februari',
    'mars',
    'april',
    'maj',
    'juni',
    'juli',
    'augusti',
    'september',
    'oktober',
    'november',
    'december',
]);

// Formatera datumet till endast tid, endast dag, endast månad, endast år och fullt datum
function format_date($date_str)
{
    $datetime_array = explode(' ', $date_str);
    $full_date = $datetime_array[0];
    $full_time = $datetime_array[1];
    $day = explode('-', $full_date)[2];
    $month = explode('-', $full_date)[1];
    $year = explode('-', $full_date)[0];

    return ['full_date' => $full_date, 'full_time' => $full_time, 'day' => $day, 'month' => $month, 'month_sv' => MONTHS_SV[intval($month) - 1], 'year' => $year];
}

?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Startsida</title>
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

    <?php include_once 'components/header.php' ?>

    <main class="main-container">
        <!-- HERO SEKTION -->
        <section class="hero-section">
            <div class="col">
                <h1>Laborum dolore ut voluptate proident. Nostrud tempor nisi quis. Lorem est ea proident deserunt velit culpa et reprehenderit esse labore sint ex.</h1>
                <div class="hero-button-container">
                    <a class="secondary-btn" href="#our-bloggers-section">Våra kreatörer</a>
                    <a class="primary-btn" href="./register.php">Gå med idag!<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="col">

                <?php
                $db_query = new DatabaseQuery;

                // Hämta två senaste inläggen
                $post_data = $db_query->get_latest_posts(2);
                $post_data = mysqli_fetch_all($post_data, MYSQLI_ASSOC);

                // Rendera inläggen
                foreach ($post_data as $post) :
                    $post_id = htmlentities($post['id']);
                    $post_title = htmlentities($post['title']);
                    $post_content = htmlentities($post['content']);
                    $post_img = htmlentities($post['image_filename']);
                    $post_author_data = $db_query->get_creator($post['author'], null)[0];
                    $post_author_firstname = htmlentities($post_author_data['firstname']);
                    $post_author_lastname = htmlentities($post_author_data['lastname']);
                    // Dela upp textsträngen för att enbart visa datum, inte tid
                    $post_publish_date = htmlentities($post['created_at']);
                    $formatted_date = format_date($post_publish_date);
                    // Ungefärligt antal ord för estimering av ungefärlig lästid
                    $post_word_count = count(explode(' ', $post_content));
                    // Ungefärlig lästid (i minuter) av artikel baserat på en genomsnittlig läshatighet på 250 ord/min
                    $post_est_reading_t = htmlentities(strval(round($post_word_count / 250)));
                ?>
                    <div class="hero-article">
                        <a class="invisible-link a-tag-rel-fix" href="<?= "blog_post.php?id=$post_id" ?>">
                            <div class="hero-article-info">
                                <div>
                                    <h3><?= $post_title ?></h3>
                                    <p><?= "$post_author_firstname $post_author_lastname | ~ $post_est_reading_t min läsning" ?></p>
                                </div>
                                <span class="date-container">
                                    <time datetime="<?= $post_publish_date ?>"><?= $formatted_date['day'] ?></time>
                                    <span>
                                        <span><?= "{$formatted_date['month_sv']}" ?> </span> <time> <?= "{$formatted_date['year']}" ?></time>
                                    </span>
                                </span>
                            </div>
                            <img src="<?= "uploads/$post_img" ?>" alt="<?= $post['image_description'] ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <!-- VÅRA FÖRFATTARE SEKTION -->
        <section id="our-bloggers-section" class="start-section our-authors-section">
            <h2>Våra kreatörer</h2>
            <div>

                <?php
                // Hämta senaste användarna
                $creator_list = $db_query->get_creator_list(5);
                $creator_list = mysqli_fetch_all($creator_list, MYSQLI_ASSOC);

                // Rendera senaste 5 användarna
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
            <a href="all_creators.php" class="primary-btn">Se alla kreatörer</a>
        </section>
    </main>

    <?php include_once 'components/footer.php' ?>
</body>

</html>