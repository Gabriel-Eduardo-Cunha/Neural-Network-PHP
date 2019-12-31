<?php
require 'vendor/readyNetworks/letterNetwork.php';

$network = Letter::untrainnedLetter();

//OXXXO
//OOXOO
//OOXOO
//OOXOO
//OXXXO


$network->trainLetter(4000);
echo '<br>Erros: ' . $network->countErrors();
echo '<br>';
echo $network->predictLetter('OXXXOOOXOOOOXOOOOXOOOXXXO');

echo '<br>';
echo '<br>';
echo '<br>';
echo $network->getNetwork()->toJSON();



?>