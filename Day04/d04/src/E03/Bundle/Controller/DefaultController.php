<?php

namespace E03\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/e03", name="e03_index")
     * @Route("/e03/", name="e03_home")
     */
    public function indexAction()
    {
        $nbrColor = $this->container->getParameter('e03.numbers_of_color');
        $colors = ['black', 'red', 'blue', 'green'];
        return $this->render('E03Bundle:Default:index.html.twig', [
            'nbrColor' => $nbrColor,
            'colors' => $colors,
        ]);
    }
}

        // <table border="1" cellspacing="0" cellpadding="10">
        //     <tr>
        //         <th>Color Name</th>
        //         <th>Preview</th>
        //     </tr>
        //     <tr>
        //         <td>Black</td>
        //         <td style="background-color: black;"></td>
        //     </tr>
        //     <tr>
        //         <td>Blue</td>
        //         <td style="background-color: blue;"></td>
        //     </tr>
        //     <tr>
        //         <td>Red</td>
        //         <td style="background-color: red;"></td>
        //     </tr>
        //     <tr>
        //         <td>Green</td>
        //         <td style="background-color: green;"></td>
        //     </tr>
        // </table>
