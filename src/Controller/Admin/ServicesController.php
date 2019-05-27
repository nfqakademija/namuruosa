<?php


namespace App\Controller\Admin;


use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServicesController extends AbstractController
{
    public function getAllServices()
    {
        $services = $this->getDoctrine()
            ->getRepository(Service::class)
            ->getAllServices();

        return $this->render('admin/services.html.twig', [
            'services' => $services,
        ]);
    }
}