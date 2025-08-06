<?php

namespace E00\Bundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpFoundation\Response;

class E00Bundle extends Bundle
{
   
    public function displayMessage()
    {
        return 'Hello world!';
    }
}