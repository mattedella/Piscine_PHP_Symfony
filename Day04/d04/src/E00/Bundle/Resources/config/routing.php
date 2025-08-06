<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('e00_homepage', new Route('/', array(
    '_controller' => 'E00Bundle:Default:index',
)));

return $collection;
