<?php

function clearedName($str) {
    $str = ucwords(str_replace(array('\'', ' ', '.'), '', $str));
    return $str;
}