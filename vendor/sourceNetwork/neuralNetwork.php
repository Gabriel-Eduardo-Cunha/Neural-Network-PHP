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
            $inputNodes = count($example) * 3;
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

    public function teachWord($word)
    {
        $word = strtolower($word);
        $wordArray = Network::generateTeachingWords($word);
        foreach ($wordArray as $value) {
            $this->insertData(Network::strToBinary($value), $word);
        }
    }

    public function teachWordExpecting($word, $expected)
    {
        $word = strtolower($word);
        $this->insertData(Network::strToBinary($word), $expected);
    }

    public function guess($input)
    {
        $index = '';
        foreach ($this->predictBinary($input) as $key => $value) {
            $index .= $this->predictBinary($input)[$key][0];
        }
        if (isset($this->data['expected'][bindec($index)])) {
            return $this->data['expected'][bindec($index)];
        } else {
            return '*Not Found';
        }
    }

    public function guessWord($string)
    {
        $string = strtolower($string);
        return $this->guess(Network::strToBinary($string));
    }

    public function printAllGuesses()
    {
        for ($i = 0; $i < count($this->data['inputs']); $i++) {
            echo Network::binToString($this->data['inputs'][$i]);
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

    public function getHitAvg()
    {
        $average = 0;
        for ($i = 0; $i < 1000; $i++) {
            $average += $this->getHit();
        }
        return round($average / 1000, 2);
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

    public function trainUntilHitAvg()
    {
    }

    public function predict($input)
    {
        $output = $this->feedForward($input)['output']->getMatrixArray();
        return $output;
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
        $json = Network::networkToJSON($this);
        return $json;
    }

    public function printNetworkStatus()
    {
        echo '<br>';
        echo '----------------STATUS FROM NETWORK----------------';
        echo '<br>';
        echo 'MSE: ' . $this->getMSE();
        echo '<br>';
        echo 'Hit Avegerage: ' . round($this->getHitAvg() * 100, 2) . '%';
        echo '<br>';
        echo 'Errors: ' . $this->countErrors();
        echo '<br>';
        echo 'Data Known: ' . count($this->data['outputs']);
        echo '<br>';
        echo 'Words Known: ' . count($this->data['expected']);
        echo '<br>';
        echo '-----------------------END OF STATUS-----------------------';
        echo '<br>';
    }

    public function printData()
    {
        echo '<br><pre>';
        print_r($this->getData());
        echo '</pre><br>';
    }

    public function printJson()
    {
        echo '<br>';
        print_r($this->toJSON());
        echo '<br>';
    }

    //PROTECTED NON-STATIC FUNCTIONS

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
        //feedforward
        $feedforward = $this->feedForward($input);

        //Backpropagation
        $this->backPropagation($feedforward, $target);
    }

    protected function feedForward($input)
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

        return array('input' => $input, 'hidden' => $hidden, 'output' => $output);
    }

    protected function backPropagation($feedforward, $target)
    {
        //Output -> Hidden
        $expected = new Matrix($target);
        $outputError = $expected->subtract($feedforward['output']);
        $d_output = $feedforward['output']->map('d_sigmoid');

        $hidden_t = $feedforward['hidden']->transpose();

        $gradient_o = $outputError->hadamard($d_output);
        $gradient_o = $gradient_o->scaleMult($this->learningRate);

        //Adjust 
        $this->bias_ho = $this->bias_ho->add($gradient_o);

        //Adjust weights__ho
        $weights_ho_deltas = $gradient_o->mult($hidden_t);
        $this->weights_ho = $this->weights_ho->add($weights_ho_deltas);

        //Hidden -> Input

        $weights_ho_t = $this->weights_ho->transpose();
        $input_t = $feedforward['input']->transpose();
        $hiddenError = $weights_ho_t->mult($outputError);
        $d_hidden = $feedforward['hidden']->map('d_sigmoid');

        $gradient_h = $hiddenError->hadamard($d_hidden);
        $gradient_h = $gradient_h->scaleMult($this->learningRate);

        //Adjust bias_ih
        $this->bias_ih = $this->bias_ih->add($gradient_h);

        //Adjust weights_ih
        $weights_ih_deltas = $gradient_h->mult($input_t);
        $this->weights_ih = $this->weights_ih->add($weights_ih_deltas);
    }

    protected function getHit()
    {
        $hits = 0;
        $total = 0;
        for ($j = 0; $j < count($this->data['expected']); $j++) {
            $word = $this->data['expected'][$j];
            $guess = $this->guessWord(Network::randomString(random_int(1, 4)) . $word . Network::randomString(random_int(1, 4)));
            if ($guess == $word) {
                $hits += 1;
            }
            $total += 1;
        }
        return $hits / $total;
    }

    //PROTECTED STATIC FUNCTIONS

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

    protected static function binToString($binary)
    {
        $binary = implode($binary);
        $string = '';
        for ($i = 0; $i < strlen($binary); $i += 7) {
            $string .= chr(bindec(substr($binary, $i, 7)));
        }
        return $string;
    }

    protected static function generateTeachingWords($word)
    {
        $wordArray = array();
        array_push($wordArray, $word);
        if (strlen($word) > 2) {
            array_push($wordArray, substr($word, 0, strlen($word) - 1));
            array_push($wordArray, substr($word, 0, strlen($word) - 2));
            array_push($wordArray, substr($word, 1, strlen($word)));
            array_push($wordArray, substr($word, 2, strlen($word)));
            array_push($wordArray, substr($word, 1, strlen($word) - 1));
        }
        array_push($wordArray, 'a' . $word . 'b');
        array_push($wordArray, 'ab' . $word . 'cd');
        array_push($wordArray, 'abc' . $word . 'def');
        array_push($wordArray, 'abcd' . $word . 'efgh');
        array_push($wordArray, 'efgh' . $word . 'abcd');
        array_push($wordArray, 'efg' . $word . 'abc');
        array_push($wordArray, 'ef' . $word . 'ab');
        array_push($wordArray, 'e' . $word . 'a');

        return $wordArray;
    }

    protected static function randomString($size)
    {
        $charSet = 'abcdefghijklmnopqrstuvwxyz';
        $string = '';
        for ($i = 0; $i < $size; $i++) {
            $char = substr($charSet, random_int(0, 25), 1);
            $string .= $char;
        }
        return $string;
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

    //SETTERS

    public function setLearningRate($value)
    {
        $this->learningRate = $value;
    }
}
