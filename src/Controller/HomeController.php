<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

        return $this->render('home/index.html.twig', [
            'someVariable' => 'NAMŲ RUOŠA',
        ]);
    }

    /**
     * @Route("/klaida/{message}", name="error", defaults={"message"="nooperation"})
     */
    public function displayErrorPage($message)
    {
        $eType = 'danger';
        $eMsg = 'Jūsų vykdoma operacija negalima.';

        if ($message == 'nooperation') {
            $eType = 'danger';
            $eMsg = 'Jūsų vykdoma operacija negalima.';
        }
        return $this->render('home/error.html.twig', [
            'someVariable' => 'NamųRuoša',
            'eType' => $eType,
            'eMsg' => $eMsg,
        ]);
    }
}
