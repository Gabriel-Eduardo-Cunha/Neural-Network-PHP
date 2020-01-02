<?php
require('vendor/sourceNetwork/neuralNetwork.php');

$json1;

$nn = new Network();

$nn->setLearningRate(0.1);

$nn->teachWord('beatiful');
$nn->teachWord('hello');
$nn->teachWord('highlight');
// $nn->teachWord('life');
// $nn->teachWord('useful');
// $nn->teachWord('saturday');
// $nn->teachWord('kill');
// $nn->teachWord('murder');



echo $nn->train(1000);

$nn->printNetworkStatus();

$nn->printJson();




