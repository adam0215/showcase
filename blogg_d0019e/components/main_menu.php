<?php
// Dynamisk URL till basmappen
$root_dir = realpath(__DIR__ . '/..');
$root_dir_alt = str_replace('students/', '~', $root_dir);
?>

<!-- Alla kreatörer -->
<div class="list-item"><a href=<?= "$root_dir_alt/all_creators.php" ?>>Alla kreatörer</a></div>