<?php

/**
 *    Retourne true si <subject> contient la chaine <search>
 */
function str_contains($search, $subject)
{
    $found = false;
    if (strpos($subject, $search) !== false) {
        $found = true;
    }
    return $found;
}