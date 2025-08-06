<?php

namespace E00\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/e00/firstpage", name="e00_firstpage")
     * @Route("/e00/firstpage/", name="e00_firstpage2")
     */
    public function indexAction()
    {
        return new Response("Hello world!");
    }
}
