<?php


namespace App\Controller\Admin;

use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ServicesController extends AbstractController
{
    /**
     * @return Response
     */
    public function getAllServices()
    {
        $services = $this->getDoctrine()
            ->getRepository(Service::class)
            ->getAllServices();

        return $this->render('admin/services.html.twig', [
            'services' => $services,
        ]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function adminDeleteService($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em
            ->getRepository(Service::class)
            ->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('admin_services');
    }
}
