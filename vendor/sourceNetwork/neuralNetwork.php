<?php
require 'vendor/sourceNetwork/matrix.php';

class Network
{
    //ATTRIBUTES

    protected $i_nodes;
    protected $h_nodes;
    protected $o_nodes;
    protected $learningRate;

    protected $bias_ih;
    protected $bias_ho;

    protected $weights_ih;
    protected $weights_ho;

    protected $data;

    //CONSTRUCT

    public function __construct($json = '')
    {
        if ($json == '') {
            $this->i_nodes = 1;
            $this->h_nodes = 1;
            $this->o_nodes = 1;

            $this->bias_ih = Matrix::GenerateRandomMatrix($this->h_nodes, 1);
            $this->bias_ho = Matrix::GenerateRandomMatrix($this->o_nodes, 1);

            $this->weights_ih = Matrix::GenerateRandomMatrix($this->h_nodes, $this->i_nodes);
            $this->weights_ho = Matrix::GenerateRandomMatrix($this->o_nodes, $this->h_nodes);

            $this->learningRate = 0.1;

            $this->data = array('inputs' => array(), 'outputs' => array(), 'expected' => array());
        } else {
            $data = json_decode($json);
            $this->learningRate = $data->learningRate;
            $this->bias_ih = new Matrix($data->bias_ih);
            $this->bias_ho = new Matrix($data->bias_ho);
            $this->weights_ih = new Matrix($data->weights_ih);
            $this->weights_ho = new Matrix($data->weights_ho);
            $this->data = (array) $data->data;
        }
    }

    //NON-STATIC FUNCTIONS

    public function insertData($example, $expected)
    {
        if (array_search($expected, $this->data['expected']) === false) {
            $this->data['expected'][count($this->data['expected'])] = $expected;
        }
        array_push($this->data['inputs'], $example);
        array_push($this->data['outputs'], array_map('intval', str_split(decbin(array_search($expected, $this->data['expected'])))));


        $outputNodes = strlen(decbin(count($this->data['expected']) - 1));
        if (count($example) > $this->getINodes()) {
            $inputNodes = count($example);
        } else {
            $inputNodes = $this->getINodes();
        }
        $hiddenNodes = round(count($this->data['inputs']) / (5 * ($outputNodes + $inputNodes)));
        if ($hiddenNodes <= 1) {
            $hiddenNodes = 2;
        }

        //Redefine the size of the network acordding to the data
        $this->changeSize($inputNodes, $hiddenNodes, $outputNodes);

        //Redefine the data size acording to the growing of the data
        for ($i = 0; $i < count($this->data['inputs']); $i++) {
            for ($j = 0; $j < $this->getINodes(); $j++) {
                if (!isset($this->data['inputs'][$i][$j])) {
                    $this->data['inputs'][$i][$j] = 0;
                }
            }
        }
        for ($i = 0; $i < count($this->data['outputs']); $i++) {
            for ($j = 0; $j < $this->getONodes(); $j++) {
                if (!isset($this->data['outputs'][$i][$j])) {
                    array_unshift($this->data['outputs'][$i], 0);
                }
            }
        }
    }

    public function teachWord($string)
    {
        $this->insertData(Network::strToBinary($string), $string);
    }

    public function guess($input)
    {
        $index = '';
        foreach ($this->predictBinary($input) as $key => $value) {
            $index .= $this->predictBinary($input)[$key][0];
        }
        return $this->data['expected'][bindec($index)];
    }

    public function guessWord($string)
    {
        return $this->guess(Network::strToBinary($string));
    }

    public function printAllGuesses()
    {
        for ($i = 0; $i < count($this->data['inputs']); $i++) {
            echo $this->data['expected'][bindec(implode($this->data['outputs'][$i]))];
            echo ' = ' . $this->guess($this->data['inputs'][$i]);
            echo '<br>';
        }
    }

    public function getMSE()
    {
        $mse = 0;
        for ($i = 0; $i < count($this->data['inputs']); $i++) {
            for ($j = 0; $j < count($this->data['outputs'][$i]); $j++) {
                $mse += pow($this->data['outputs'][$i][$j] - $this->predict($this->data['inputs'][$i])[$j][0], 2);
            }
        }
        return round($mse, 5);
    }

    public function countErrors()
    {
        $errors = 0;
        for ($i = 0; $i < count($this->data['inputs']); $i++) {
            if (Matrix::convertSimpleArray($this->data['outputs'][$i]) != $this->predictBinary($this->data['inputs'][$i])) {
                $errors += 1;
            }
        }
        return $errors;
    }

    public function train($attempts)
    {
        for ($i = 0; $i < $attempts; $i++) {
            for ($j = 0; $j < count($this->data['inputs']); $j++) {
                $this->practice($this->data['inputs'][$j], $this->data['outputs'][$j]);
            }
        }
    }

    public function trainUntilMSE($targetMSE)
    {
        $startTime = time();
        $attempts = 0;
        while ($this->getMSE() > $targetMSE) {
            for ($j = 0; $j < count($this->data['inputs']); $j++) {
                $this->practice($this->data['inputs'][$j], $this->data['outputs'][$j]);
            }
            $attempts++;
        }
        $endTime = date("s", time() - $startTime);
        return '<br>Trained: ' . $attempts . '<br>During: ' . $endTime . ' seconds';
    }

    public function predict($input)
    {

        //Input -> Hidden
        $input = new Matrix($input);
        $hidden = $this->weights_ih->mult($input);
        $hidden = $hidden->add($this->bias_ih);
        $hidden = $hidden->map('sigmoid');

        //Hidden -> Output
        $output = $this->weights_ho->mult($hidden);
        $output = $output->add($this->bias_ho);
        $output = $output->map('sigmoid');

        return $output->getMatrixArray();
    }

    public function predictBinary($input)
    {
        $output = $this->predict($input);
        foreach ($output as $key => $value) {
            $output[$key][0] = round($output[$key][0]);
        }
        return $output;
    }

    public function toJSON()
    {
        return Network::networkToJSON($this);
    }

    public function printNetworkStatus()
    {
        echo '<br>';
        echo '----------------STATUS FROM NETWORK----------------';
        echo '<br>';
        echo 'MSE: ' . $this->getMSE();
        echo '<br>';
        echo 'Errors: ' . $this->countErrors();
        echo '<br>';
        $this->printAllGuesses();
        echo '-----------------------END OF STATUS-----------------------';
        echo '<br>';
    }

    public function printData()
    {
        echo '<br><pre>';
        print_r($this->getData());
        echo '</pre><br>';
    }

    //PROTECTED FUNCTIONS

    protected function changeSize($i_nodes, $h_nodes, $o_nodes)
    {
        $this->i_nodes = $i_nodes;
        $this->h_nodes = $h_nodes;
        $this->o_nodes = $o_nodes;

        $this->bias_ih = Matrix::GenerateRandomMatrix($this->h_nodes, 1);
        $this->bias_ho = Matrix::GenerateRandomMatrix($this->o_nodes, 1);

        $this->weights_ih = Matrix::GenerateRandomMatrix($this->h_nodes, $this->i_nodes);
        $this->weights_ho = Matrix::GenerateRandomMatrix($this->o_nodes, $this->h_nodes);
    }

    protected function practice($input, $target)
    {
        //Input -> Hidden
        $input = new Matrix($input);
        $hidden = $this->weights_ih->mult($input);
        $hidden = $hidden->add($this->bias_ih);
        $hidden = $hidden->map('sigmoid');

        //Hidden -> Output
        $output = $this->weights_ho->mult($hidden);
        $output = $output->add($this->bias_ho);
        $output = $output->map('sigmoid');

        //Backpropagation

        //Output -> Hidden
        $expected = new Matrix($target);
        $outputError = $expected->subtract($output);
        $d_output = $output->map('d_sigmoid');

        $hidden_t = $hidden->transpose();

        $gradient_o = $outputError->hadamard($d_output);
        $gradient_o = $gradient_o->scaleMult($this->learningRate);

        //Adjust 
        $this->bias_ho = $this->bias_ho->add($gradient_o);

        //Adjust weights__ho
        $weights_ho_deltas = $gradient_o->mult($hidden_t);
        $this->weights_ho = $this->weights_ho->add($weights_ho_deltas);

        //Hidden -> Input

        $weights_ho_t = $this->weights_ho->transpose();
        $input_t = $input->transpose();
        $hiddenError = $weights_ho_t->mult($outputError);
        $d_hidden = $hidden->map('d_sigmoid');

        $gradient_h = $hiddenError->hadamard($d_hidden);
        $gradient_h = $gradient_h->scaleMult($this->learningRate);

        //Adjust bias_ih
        $this->bias_ih = $this->bias_ih->add($gradient_h);

        //Adjust weights_ih
        $weights_ih_deltas = $gradient_h->mult($input_t);
        $this->weights_ih = $this->weights_ih->add($weights_ih_deltas);
    }

    protected static function strToBinary($string)
    {
        $array = array();
        for ($i = 0; $i < strlen($string); $i++) {
            for ($j = 0; $j < strlen(decbin(ord(substr($string, $i, 1)))); $j++) {
                array_push($array, array_map('intval', str_split(decbin(ord(substr($string, $i, 1)))))[$j]);
            }
        }
        return $array;
    }

    //STATIC FUNCTIONS

    public static function networkToJSON($network)
    {
        $array = array(
            'i_nodes' => $network->getINodes(),
            'h_nodes' => $network->getHNodes(),
            'o_nodes' => $network->getONodes(),
            'learningRate' => $network->getLearnigRate(),
            'bias_ih' => $network->getBiasIH()->getMatrixArray(),
            'bias_ho' => $network->getBiasHO()->getMatrixArray(),
            'weights_ih' => $network->getWeightsIH()->getMatrixArray(),
            'weights_ho' => $network->getWeightsHO()->getMatrixArray(),
            'data' => $network->getData()
        );
        return json_encode($array);
    }

    //GETTERS

    public function getINodes()
    {
        return $this->i_nodes;
    }

    public function getHNodes()
    {
        return $this->h_nodes;
    }

    public function getONodes()
    {
        return $this->o_nodes;
    }

    public function getBiasIH()
    {
        return $this->bias_ih;
    }

    public function getBiasHO()
    {
        return $this->bias_ho;
    }

    public function getWeightsIH()
    {
        return $this->weights_ih;
    }

    public function getWeightsHO()
    {
        return $this->weights_ho;
    }

    public function getLearnigRate()
    {
        return $this->learningRate;
    }

    public function getData()
    {
        return $this->data;
    }
}
