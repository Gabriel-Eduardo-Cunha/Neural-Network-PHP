<?php

require 'vendor/sourceNetwork/mathFunctions.php';

class Matrix
{
    //ATTRIBUTES

    protected $matrix;
    protected $rows;
    protected $cols;

    //CONSTRUCT

    public function __construct($array)
    {
        if (!isset($array[0][0])) {
            $array = Matrix::convertSimpleArray($array);
        }
        $this->rows = count($array);
        $this->cols = count($array[0]);
        for ($r = 0; $r < $this->rows; $r++) {
            for ($c = 0; $c < $this->cols; $c++) {
                $this->matrix[$r][$c] = $array[$r][$c];
            }
        }
    }

    //STATIC CONSTRUCT

    public static function generateRandomMatrix($rows, $cols)
    {
        $matrix = new self(Matrix::GenerateRandomMatrixArray($rows, $cols));
        return $matrix;
    }

    //NON-STATIC FUNCTIONS

    public function toText()
    {
        return Matrix::matrixToText($this);
    }

    public function print()
    {
        print_r($this->toText());
    }

    public function add($matrix)
    {
        $m1 = $this->getMatrixArray();
        $m2 = $matrix->getMatrixArray();
        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $this->getCols(); $j++) {
                $result[$i][$j] = $m1[$i][$j] + $m2[$i][$j];
            }
        }
        return new Matrix($result);
    }

    public function subtract($matrix)
    {
        $m1 = $this->getMatrixArray();
        $m2 = $matrix->getMatrixArray();
        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $this->getCols(); $j++) {
                $result[$i][$j] = $m1[$i][$j] - $m2[$i][$j];
            }
        }
        return new Matrix($result);
    }


    public function mult($matrix)
    {
        $m1 = $this->getMatrixArray();
        $m2 = $matrix->getMatrixArray();

        if ($this->getCols() >= $matrix->getRows()) {
            $smallInternal = $matrix->getRows();
        } else {
            $smallInternal = $this->getCols();
        }

        for ($row = 0; $row <  $this->getRows(); $row++) {
            for ($col = 0; $col <  $matrix->getCols(); $col++) {
                $resultArray[$row][$col] = 0;
                for ($internal = 0; $internal < $smallInternal; $internal++) {
                    $resultArray[$row][$col] += $m1[$row][$internal] * $m2[$internal][$col];
                }
            }
        }
        return new Matrix($resultArray);
    }

    public function scaleMult($number)
    {
        $m1 = $this->getMatrixArray();
        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $this->getCols(); $j++) {
                $result[$i][$j] = $m1[$i][$j] * $number;
            }
        }
        return new Matrix($result);
    }

    public function hadamard($matrix)
    {
        $m1 = $this->getMatrixArray();
        $m2 = $matrix->getMatrixArray();
        for ($i = 0; $i < $this->getRows(); $i++) {
            for ($j = 0; $j < $this->getCols(); $j++) {
                $result[$i][$j] = $m1[$i][$j] * $m2[$i][$j];
            }
        }
        return new Matrix($result);
    }

    public function map($function)
    {
        $matrix = $this->getMatrixArray();
        for ($r = 0; $r < $this->getRows(); $r++) {
            for ($c = 0; $c < $this->getCols(); $c++) {
                $resultArray[$r][$c] = $function($matrix[$r][$c]);
            }
        }
        return new Matrix($resultArray);
    }

    public function transpose()
    {
        $array = $this->getMatrixArray();
        $transposedArray = array();
        for ($r = 0; $r < $this->getRows(); $r++) {
            for ($c = 0; $c < $this->getCols(); $c++) {
                $transposedArray[$c][$r] = $array[$r][$c];
            }
        }
        return new Matrix($transposedArray);
    }

    //STATIC FUNCTIONS

    public static function mapMatrix($matrix, $function)
    {
        $matrixArray = $matrix->getMatrixArray();
        for ($r = 0; $r < $matrix->getRows(); $r++) {
            for ($c = 0; $c < $matrix->getCols(); $c++) {
                $resultArray[$r][$c] = $function($matrixArray[$r][$c]);
            }
        }
        return new Matrix($resultArray);
    }

    public static function matrixToText($matrix)
    {
        $rows = $matrix->getRows();
        $cols = $matrix->getCols();
        $matrixArray = $matrix->getMatrixArray();
        $text = "";
        for ($r = 0; $r < $rows; $r++) {
            $text .= "<br>";
            for ($c = 0; $c < $cols; $c++) {
                $text .= $matrixArray[$r][$c];
                if (($cols - 1) != $c) {
                    $text .= " | ";
                }
            }
        }
        return $text;
    }

    public static function GenerateRandomMatrixArray($rows, $cols)
    {
        for ($r = 0; $r < $rows; $r++) {
            for ($c = 0; $c < $cols; $c++) {
                $matrix[$r][$c] = random_int(-10, 10) / 10;
            }
        }

        return $matrix;
    }

    //PROTECTED STATIC FUNCIONS

    public static function convertSimpleArray($array)
    {
        if (is_array($array)) {
            for ($i = 0; $i < count($array); $i++) {
                $newArray[$i][0] = $array[$i];
            }
            return $newArray;
        } else {
            print_r('Not an Array object, Fatal Error');
            exit;
        }
    }

    //GETTERS AND SETTERS

    public function setMatrix($newMatrix)
    {
        if (!isset($newMatrix[0][0])) {
            $newMatrix = Matrix::convertSimpleArray($newMatrix);
        }
        $this->matrix = $newMatrix;
        $this->rows = count($newMatrix);
        $this->cols = count($newMatrix[0]);
    }

    public function getMatrixArray()
    {
        return $this->matrix;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getCols()
    {
        return $this->cols;
    }
}
