<?php
// Dynamisk filväg till basmappen
$root_dir = dirname('..');
?>

<footer>
    <nav class="main-nav">
        <ul>
            <li>
                <div><a class="nav-logo" href="<?= $root_dir ?>">idk</a></div>
            </li>

            <li id="footer-author-date">
                <div>
                    <p>Adam Gustafsson | <?= date('Y') ?> </p>
                </div>
            </li>

            <li>
                <?php include 'main_menu.php' ?>
            </li>
        </ul>
    </nav>
</footer>