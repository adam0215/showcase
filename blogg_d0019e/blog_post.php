<?php
// Om inget "id" finns i urlen, skicka användaren till "inget inlägg hittades"-sidan
if (!isset($_GET['id'])) {
    header("Location: post_not_found.php");
    die();
}
require_once 'db/db_query.php';

if (!isset($_SESSION)) session_start();

// Sanera eventuell användarinmatning
$url_post_id = htmlentities($_GET['id']);

$db_query = new DatabaseQuery;

// Hämta inläggsdata
$post_data = $db_query->get_post($url_post_id);

$post_id = $post_data['id'];
$post_title = htmlentities($post_data['title']);
// NL2BR för att behålla de originella radbrytningarna. Viktigt för att behålla texten mening och författarens budskap och uttryck.
$post_content = nl2br(htmlentities($post_data['content']));
$post_img = htmlentities($post_data['image_filename']);
$post_img_desc = htmlentities($post_data['image_description']);
$post_author_data = $db_query->get_creator($post_data['author'], null)[0];
$post_author_firstname = htmlentities($post_author_data['firstname']);
$post_author_lastname = htmlentities($post_author_data['lastname']);
$post_author_username = htmlentities($post_author_data['username']);
$post_author_profile_pic = htmlentities($db_query->get_image_filename($post_author_data['profile_picture']));
// Dela upp textsträngen för att enbart visa datum, inte tid
$post_publish_date = explode(' ', $post_data['created_at'])[0];

// Ungefärligt antal ord för estimering av ungefärlig lästid
$post_word_count = count(explode(' ', $post_content));
// Ungefärlig lästid (i minuter) av artikel baserat på en genomsnittlig läshatighet på 250 ord/min
$post_est_reading_t = htmlentities(strval(round($post_word_count / 250)));

$reactions = $db_query->get_all_post_reactions($post_id);
$reaction_counts = count_reactions($reactions);

// Om användaren är inloggad
if (isset($_SESSION['id'])) {
    $current_user_id = $_SESSION['id'];
    $current_user_reaction = get_user_post_reaction($current_user_id, $reactions);
    $user_has_reacted = !empty($current_user_reaction);
} else {
    // Om ingen är inloggad, sätt användarens reaktion till null därför att reaktioner kräver inloggning
    $current_user_reaction = null;
}

// Funktion för att hämta användarens reaktion på inlägget, om den gjort en, eller ta reda på om de inte gjort än
function get_user_post_reaction($user_id, $reaction_arr)
{
    if ($reaction_arr === null) return null;

    // Leta igenom arrayen efter en reagerare som har samma id som den inloggade användaren
    foreach ($reaction_arr as $reaction) {
        $type = $reaction['type'];
        $reactor = $reaction['reactor'];

        // Om den inloggade användaren har reagerat på inlägget, returnera reaktionstypen
        if ($reactor === $user_id) {
            return $type;
        }
    }
    // Annars returnera null
    return null;
}

// Räkna antalet av varje reaktion på inlägget
function count_reactions($reaction_arr)
{
    // Skapa en array med reaktionerna och initialisera varje nyckel till ett värde av noll
    $count_arr = [
        'mindblown' => 0,
        'laughing' => 0,
        'celebrating' => 0,
        'thinking' => 0,
    ];

    if ($reaction_arr === null) return $count_arr;

    // Gå igenom arrayen med reaktioner på inlägget
    foreach ($reaction_arr as $reaction) {
        $type = $reaction['type'];
        // Addera till respektive nyckel i arrayen
        $count_arr[$type]++;
    }

    // Returnera arrayen med reaktionsantalen
    return $count_arr;
}
?>

<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $post_title ?></title>
    <link rel="stylesheet" href="styles/styles.css">
    <script src="javascript/blog_post.js" defer></script>

    <!-- Taggar för fina förhandsvisningar på sociala medier och tjänster såsom Discord -->
    <meta property="og:title" content="<?= $post_title ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= "https://utbweb.its.ltu.se/~adagus-1/Labb4a/blog_post.php?id=$post_id" ?>" />
    <meta property="og:image" content="<?= "https://utbweb.its.ltu.se/~adagus-1/Labb4a/uploads/$post_img" ?>" />
    <meta property="og:description" content="<?= substr($post_content, 0, 240) ?>" />
</head>

<body>

    <?php include_once 'components/header.php' ?>

    <main class="main-container post-main">

        <article class="post-container">

            <figure class="post-cover-container">
                <img class="post-cover" src="uploads/<?= $post_img ?>" alt="<?= $post_img_desc ?>">
                <figcaption class="image-desc-text"><?= $post_img_desc ?></figcaption>
            </figure>

            <div class="post-heading-container">
                <h1 class="post-title"><?= $post_title ?></h1>
                <span>~ <?= $post_est_reading_t ?> min läsning</span>
            </div>

            <p class="post-content"><?= $post_content ?></p>

            <div class="post-info-container">
                <a href="creator.php?creator=<?= $post_author_username ?>" class="invisible-link">
                    <div class="card-container card-hover sm-user-card-container">
                        <img src="uploads/<?= $post_author_profile_pic ?>" alt="<?= $post_img_desc ?>" class="profile-pic">
                        <div class="sm-user-card-info">
                            <h6><?= $post_author_firstname . ' ' . $post_author_lastname ?></h6>
                            <span><?= $post_author_username ?></span>
                        </div>
                        <span class="sm-card-divider"></span>
                        <time><?= $post_publish_date ?></time>
                    </div>
                </a>

                <div id="post-reactions">
                    <!-- Ta bort "card-hover"-klassen om användaren inte är inloggad -->
                    <!-- Lägg till "reacted" på den reaktionsknapp som användaren har reagerat på (ifall den har reagerat) -->

                    <!-- Mindblown -->
                    <button class="card-container <?= !isset($_SESSION['id']) ? '' : 'card-hover' ?> <?= $current_user_reaction === 'mindblown' ? 'reacted' : '' ?> reaction-container" name="mindblown" data-reactioncount=<?= "{$reaction_counts['mindblown']}" ?>>
                        <span class="reaction-count">🤯 <span><?= "{$reaction_counts['mindblown']}" ?></span></span>
                    </button>

                    <!-- Laughing -->
                    <button class="card-container <?= !isset($_SESSION['id']) ? '' : 'card-hover' ?> <?= $current_user_reaction === 'laughing' ? 'reacted' : '' ?> reaction-container" name="laughing" data-reactioncount=<?= "{$reaction_counts['laughing']}" ?>>
                        <span class="reaction-count">😂 <span><?= "{$reaction_counts['laughing']}" ?></span></span>
                    </button>

                    <!-- Celebrating -->
                    <button class="card-container <?= !isset($_SESSION['id']) ? '' : 'card-hover' ?> <?= $current_user_reaction === 'celebrating' ? 'reacted' : '' ?> reaction-container" name="celebrating" data-reactioncount=<?= "{$reaction_counts['celebrating']}" ?>>
                        <span class="reaction-count">🥳 <span><?= "{$reaction_counts['celebrating']}" ?></span></span>
                    </button>

                    <!-- Thinking -->
                    <button class="card-container <?= !isset($_SESSION['id']) ? '' : 'card-hover' ?> <?= $current_user_reaction === 'thinking' ? 'reacted' : '' ?> reaction-container" name="thinking" data-reactioncount=<?= "{$reaction_counts['thinking']}" ?>>
                        <span class="reaction-count">🤔 <span><?= "{$reaction_counts['thinking']}" ?></span></span>
                    </button>
                </div>

            </div>

        </article>


        <!-- INLÄGGSLISTA -->
        <?php
        $other_posts_data = $db_query->get_latest_from_creator(/* ID:et för författaren av inlägget ovan */$post_author_data['id'], 5);
        $other_posts_data = mysqli_fetch_all($other_posts_data, MYSQLI_ASSOC);

        // Om fler inlägg från användaren endast ger tillbaka ett inlägg, och det inlägget är samma som det ovan, sluta köra skriptet.
        if (count($other_posts_data) === 1 && $other_posts_data[0]['id'] === $post_data['id']) :
        ?>

            <!-- STÄNG MAINTAG IFALL INGET INLÄGG FINNS -->
    </main>
<?php else : ?>

    <section class="more-from-section">
        <h2>Mer från <?= $post_author_firstname ?> <?= $post_author_lastname ?> </h2>

        <div class="post-list">
            <?php
            // Rendera andra inlägg
            foreach ($other_posts_data as $post) :
                // Om id:t på den hämtade posten är samma som den som visas ovan, hoppa över att rendera den.
                if ($post['id'] === $post_data['id']) continue;

                $post_id = $post['id'];
                $post_title = $post['title'];
                $post_content = $post['content'];
                // Begränsa antalet ord som skickas med i förhandsvisningen för att inte lägga in hela artikeln i förhandsvisningen (Textlängden trunkeras också genom CSS)
                $post_content_limited = substr($post_content, 0, 300);
                $post_img_data = $db_query->get_image_data($post['image'])[0];
                // Ungefärligt antal ord för estimering av ungefärlig lästid
                $post_word_count = count(explode(' ', $post_content));
                // Ungefärlig lästid (i minuter) av artikel baserat på en genomsnittlig läshatighet på 250 ord/min
                $post_est_reading_t = strval(round($post_word_count / 250));
            ?>
                <a class="invisible-link" href="<?= "blog_post.php?id=$post_id" ?>">
                    <div class="post-list-item">
                        <div>
                            <h3><?= $post_title ?></h3>
                            <span><?= "~ $post_est_reading_t min läsning" ?></span>
                            <p><?= $post_content_limited ?></p>
                        </div>
                        <div>
                            <img src="uploads/<?= $post_img_data['filename'] ?>" alt="<?= $post_img_data['description'] ?>">
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>

        </div>

        <?= "</section>" ?>

        </main>
    <?php endif ?>

    <?php include_once 'components/footer.php' ?>

</body>

</html>