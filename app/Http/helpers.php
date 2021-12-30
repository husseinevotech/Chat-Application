<?php

function makeImageFromName($name) {
    $userImage = "";
    $shortName = "";

    $names = explode(" ", $name);

    foreach ($names as $w) {
        $shortName .= $w[0];
    }

    $userImage = sprintf("<div class=name-image bg-primary>%s</div>", $shortName);
    return $userImage;
}
