<?php

namespace E02\Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use E02\Bundle\Form\ContactType;
// use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Constraints as Assert;



class DefaultController extends Controller
{
    /**
     * @Assert\NotBlank(message="Message is required")
     * @Assert\Length(min=1)
     * @Route("/e02", name="e02_index")
     * @Route("/e02/", name="e02_home")
     */
    public function indexAction(Request $request) {
        $fileName = $this->get('kernel')->getRootDir() . '/../' . $this->container->getParameter('file_name');
        $form = $this->createForm(new ContactType());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $message = $data['message'];
            $timestamp = $data['timestamp'];
            $date = date('Y-m-d H:i:s');

            if ($timestamp === 'yes') {
                file_put_contents($fileName,'[' . $date . "] " . $message . "\n", FILE_APPEND);
                return $this->render('E02Bundle:Default:result.html.twig', [
                    'message' => '[' . $date . "] " . $message,
                ]);
            }
            else {
                file_put_contents($fileName, $message . "\n", FILE_APPEND);
                return $this->render('E02Bundle:Default:result.html.twig', [
                    'message' => $message,
                ]);
            }
        }
        return $this->render('E02Bundle:Default:index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
