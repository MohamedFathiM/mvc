<?php

namespace System\View;

interface ViewInterface
{
    /**
     * get the view output
     * 
     * @return string
     */
    public function getOutput();

    /**
     * convert the view object to string in printing 
     * i.e echo $object 
     * 
     * @return string
     */
    public function __toString();
}
