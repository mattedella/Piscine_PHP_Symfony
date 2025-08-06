<?php

namespace E01\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    private $categories = [
        'controller', 'routing', 'templating', 'doctrine', 'testing',
        'validation', 'forms', 'security', 'cache', 'translations', 'services'
    ];
    /**
     * @Route("/e01", name="e01_listing")
     * @Route("/e01/", name="e01_listing2")
     * @Route("/e01/{category}", name="e01_category", requirements={"category"="[a-z]+"})
     */
    public function indexAction($category = null)
    {
        if ($category && in_array($category, $this->categories)) {
            $template = 'E01Bundle:Default:' . $category . '.html.twig';
            if ($this->get('templating')->exists($template)) {
                return $this->render($template, [
                    'categories' => $this->categories,
                    'category' => $category,
                ]);
            }
        }
        return $this->render('E01Bundle:Default:index.html.twig', [
            'categories' => $this->categories,
        ]);
    }

}
