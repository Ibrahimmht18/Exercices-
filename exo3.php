<?php
 
function my_str_contains($phrase, $mot) {

    $longueurPhrase = strlen($phrase);

    $longueurMot = strlen($mot);
 
    if ($longueurMot === 0) return true; 

    if ($longueurMot > $longueurPhrase) return false;
 
    for ($position = 0; $position <= ($longueurPhrase - $longueurMot); $position++) {

        $estIdentique = true;
 
        for ($i = 0; $i < $longueurMot; $i++) {

            if ($phrase[$position + $i] !== $mot[$i]) {

                $estIdentique = false;

                break;

            }

        }
 
        if ($estIdentique) {

            return true;

        }

    }
 
    return false;

}
 
$texte = "Il fait beau";

if (my_str_contains($texte, "beau")) {

    echo "Trouvé !";

}
 
?>
 