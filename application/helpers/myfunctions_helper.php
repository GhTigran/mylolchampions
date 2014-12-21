<?php

function clearedName($str) {
    $str = ucwords(str_replace(['\'', ' ', '.'], '', $str));
    return $str;
}