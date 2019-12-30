<?php

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

    //CONSTRUCT

    public function __construct($i_nodes, $h_nodes, $o_nodes)
    {
        $this->i_nodes = $i_nodes;
        $this->h_nodes = $h_nodes;
        $this->o_nodes = $o_nodes;

        $this->bias_ih = Matrix::GenerateRandomMatrix($this->h_nodes, 1);
        $this->bias_ho = Matrix::GenerateRandomMatrix($this->o_nodes, 1);

        $this->weights_ih = Matrix::GenerateRandomMatrix($this->h_nodes, $this->i_nodes);
        $this->weights_ho = Matrix::GenerateRandomMatrix($this->o_nodes, $this->h_nodes);

        $this->learningRate = 0.1;
    }

    //STATIC CONSTRUCT

    public static function JSONToNetwork($json) 
    {
        $data = json_decode($json);
        $network = new self($data->i_nodes, $data->h_nodes, $data->o_nodes);
        $network->learningRate = $data->learningRate;
        $network->bias_ih = new Matrix($data->bias_ih);
        $network->bias_ho = new Matrix($data->bias_ho);
        $network->weights_ih = new Matrix($data->weights_ih);
        $network->weights_ho = new Matrix($data->weights_ho);

        return $network;
    }

    //NON-STATIC FUNCTIONS

    public function train($input, $target)
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

        //Adjust bias_ho
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

    public function toJSON()
    {
        return Network::networkToJSON($this);
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
            'weights_ho' => $network->getWeightsHO()->getMatrixArray()
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
}
