<?php

function sigmoid($value)
{
    return 1 / (1 + pow(M_E, -$value));
}

function d_sigmoid($value)
{
    return $value * (1 - $value);
}

?>