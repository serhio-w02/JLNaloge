<?php

// namen te skripte je, da preusmeri uporabnika nazaj na stran
// s katere je prišel (torej osveži stran)

// redirect iz strani s formo je potreben zato, da se
// podatki, poslani preko forme počistijo

$referer = $_SERVER['HTTP_REFERER'];

header("location:". $referer);
exit;

?>