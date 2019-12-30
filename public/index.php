<?php
require('vendor/matrix.php');
require('vendor/neuralNetwork.php');
require('vendor/mathFunctions.php');


//HERE YOU DECIDE THE SIZE OF INPUT NODES, HIDDEN NODES AND OUTPUT NODES
//INPUT NODES MUST MATCH THE AMOUNTS OF INPUTS THAT YOUR NETWORK WILL RECEIVE
//OUTPUT NODES MUST MATCH THE AMOUNT OF OUTPUTS THAT YOUR NETWORK WILL DO
//HIDDEN NODES INCREASES THE ACCURACY OF THE NETWORK, BUT USUALLY THEY CAN DO THE SUM OF THE IN AND OUT NODES
$network = new Network(2,5,1);

//HERE YOU DECIDE THE INPUTS AND THE CORRECT OUTPUT THAT YOUR NETWORK MUST HAVE FOR EACH SITUATION
//THE DEFAULT CONFIGURATION IS THE INPUTS OF THE XORPROBLEM
//00 MUST BE 0
//11 MUST BE 0
//10 MUST BE 1
//01 MUST BE 1
$data = array(
    'inputs' => array(
        0 => array(0 => 0, 1 => 0),
        1 => array(0 => 1, 1 => 0),
        2 => array(0 => 0, 1 => 1),
        3 => array(0 => 1, 1 => 1),
    ),
    'outputs' => array(
        0 => array(0 => 0),
        1 => array(0 => 1),
        2 => array(0 => 1),
        3 => array(0 => 0),
    )
);

//HERE YOU TRAIN YOUR NETWORK
//THE MORE TRAININGATTEMPTS, MORE ACCURACY THE NETWORK WILL HAVE
$trainingAttempts = 1000000;
for ($i = 0; $i < $trainingAttempts; $i++) {
    $index = random_int(0, count($data['outputs']) - 1);
    $network->train($data['inputs'][$index], $data['outputs'][$index]);
}

//HERE PRINTS THE NETWORK JSON
echo $network->toJSON();

//HERE PRINTS THE AWNSER OF THE NETWORK
//THE DEFAULT AWNSER HERE IS THE AWNSER OF THE XORPROBLEM
echo '<br>Resposta da rede para 00: ';
echo $network->predict($data['inputs'][0])[0][0];
echo '<br>Resposta da rede para 10: ';
echo $network->predict($data['inputs'][1])[0][0];
echo '<br>Resposta da rede para 01: ';
echo $network->predict($data['inputs'][2])[0][0];
echo '<br>Resposta da rede para 11: ';
echo $network->predict($data['inputs'][3])[0][0];

