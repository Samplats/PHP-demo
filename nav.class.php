<?php

class Navigation {
    public static function render() {
        echo '
        <nav>
            <a href="index.php?option=all">Alles</a>
            <a href="schoenen.php?option=schoenen">Schoenen</a>
            <a href="tassen.php?option=tassen">Tassen</a>
            <a href="basketbal.php?option=basketballen">Basketballen</a>
            <a href="tenues.php?option=accessoires">Tenues</a>
            <a href="accesoires.php?option=accessoires">Accessoires</a>
        </nav>';
    }
}
?>
