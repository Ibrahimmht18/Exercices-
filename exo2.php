<?php
$chaine = "Bonjour chef";
 
function my_strrev($chaine) {
    $inverse = '';
    for ($i = strlen($chaine) - 1; $i >= 0; $i--) {
        $inverse .= $chaine[$i];
    }
    return $inverse;
}
echo my_strrev($chaine);