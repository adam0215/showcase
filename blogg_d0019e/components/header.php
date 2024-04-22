<?php
$root_dir = realpath(__DIR__ . '/..');
$root_dir_alt = str_replace('students/', '~', $root_dir);

require_once $root_dir . '/db/db_query.php';

// Finns ingen session, starta en så att ev. sessionsvariabler kan kommas åt
if (!isset($_SESSION)) session_start();

// Om sessionen har ett id / om en användare är inloggad
if (isset($_SESSION['id'])) {
    $db_query = new DatabaseQuery;

    // Hämta användaruppgifter
    $user_firstname = $_SESSION['firstname'] ?? '';
    $user_lastname = $_SESSION['lastname'] ?? '';
    $user_username = $_SESSION['username'] ?? '';
    $user_profile_pic_data = $db_query->get_image_data($_SESSION['profile_picture'])[0];
    $user_profile_pic = $user_profile_pic_data['filename'] ?? '';
    $user_profile_pic_desc = $user_profile_pic_data['description'] ?? '';
}

// LOGGA UT
if (isset($_POST['log-out'])) {
    // Förstör session och rensa variabler ifall "Logga ut" trycks
    session_unset();
    session_destroy();
    header("Location: $root_dir_alt");
    // Terminera skriptet
    die();
}
?>
<header>
    <nav class="main-nav">
        <ul>
            <!-- LOGGA -->
            <li class="list-item-container">
                <div class="list-item"><a class="nav-logo" href="<?= "$root_dir_alt/" ?>">idk</a></div>
            </li>

            <li class="list-item-container">
                <?php include 'main_menu.php' ?>

                <?php if (isset($_SESSION['id'])) : ?>

                    <!-- INLOGGAD ANVÄNDARE -->
                    <div class="user-card-btn-container list-item">
                        <div class="pos-rel">
                            <div id="nav-user-card" class="card-container card-hover sm-user-card-container">
                                <img src="<?= "$root_dir_alt/uploads/$user_profile_pic" ?>" alt="<?= $user_profile_pic_desc ?>" class="profile-pic">
                                <div class="sm-user-card-info">
                                    <h6><?= $user_firstname . ' ' . $user_lastname ?></h6>
                                    <span><?= $user_username ?></span>
                                </div>
                                <svg id="user-card-caret" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                </svg>
                            </div>

                            <!-- LISTMENY -->
                            <div id="nav-user-menu" class="nav-user-menu card-container d-none">
                                <ul>
                                    <li>
                                        <a href="<?= "$root_dir_alt/admin/" ?>" class="teritary-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Profil
                                        </a>
                                    </li>
                                    <li>
                                        <form method="POST">
                                            <button class="teritary-btn alerting-text" name="log-out"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                                </svg>Logga ut
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- PLUSKNAPP/SKAPA INLÄGG KNAPP -->
                        <div>
                            <a class="primary-btn expanding-btn" href="<?= $root_dir_alt . '/admin/make_post.php?location=' . urlencode($_SERVER["REQUEST_URI"])  ?>" title="Skapa ett nytt inlägg">
                                <span>Nytt inlägg</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <script>
                        const menu = document.getElementById('nav-user-menu');
                        const userCard = document.getElementById('nav-user-card')
                        const caret = document.getElementById('user-card-caret')

                        // Lyssna efter klick i hela dokumentet
                        document.addEventListener('click', (e) => {
                            // STÄNG ANVÄNDARMENY IFALL ANVÄNDAREN KLICKAR UTANFÖR
                            // Om klicket inte är på varken nav-menyn eller användarkortet i navbaren:
                            if (!e.target.closest('#nav-user-card') && !e.target.closest('#nav-user-menu')) {
                                // Lägg till "avstängningsklassen"
                                menu.classList.add('d-none')
                            }
                        })

                        // Menyanimationer
                        const menuOpeningAnimation = [{
                                transform: 'translateY(-4rem)'
                            },
                            {
                                transform: 'translateY(0rem)'
                            },
                        ]

                        const menuClosingAnimation = [{
                                transform: 'translateY(0rem)'
                            },
                            {
                                transform: 'translateY(-4rem)'
                            },
                        ]

                        const menuAnimationTimings = {
                            iterations: 1,
                            duration: 200,
                            easing: 'ease-in-out'
                        }

                        // ---

                        // Lyssna efter klick på användarkortet
                        userCard.addEventListener('click', (e) => {
                            // Om menyn är gömd
                            if (menu.classList.contains('d-none')) {
                                // Animera ner menyn
                                menu.animate(menuOpeningAnimation, menuAnimationTimings)
                                // Visa meny
                                menu.classList.remove('d-none')
                            } else {
                                // Animera upp menyn
                                const closingMenu = menu.animate(menuClosingAnimation, menuAnimationTimings)
                                // Vänta tills animationen är färdig
                                closingMenu.onfinish = () => {
                                    // Göm meny
                                    menu.classList.add('d-none')
                                }
                            }
                            // Rotera ikon
                            if (caret.style.rotate === '0deg' || caret.style.rotate === '') {
                                caret.style.rotate = '180deg'
                            } else {
                                caret.style.rotate = '0deg'
                            }
                        })
                    </script>

                <?php else : ?>
                    <!-- LOGGA IN KNAPPT -->
                    <div class="list-item"><a class="primary-btn" href="<?= $root_dir_alt . '/login.php?location=' . urlencode($_SERVER["REQUEST_URI"]) ?>">Logga in</a></div>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</header>