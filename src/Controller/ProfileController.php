<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $userId = $this->getUser()->getId();
        $userInfo = $this->getDoctrine()->getRepository('App:User')->find($userId);

        dump($userInfo);
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'firstName' => $userInfo->getFirstName(),
            'lastName' => $userInfo->getlastName(),
        ]);
    }
    /**
     * @Route("/editProfile", name="editProfile")
     */
    public function editProfile()
    {
        $userId = $this->getUser()->getId();
        $userInfo = $this->getDoctrine()->getRepository('App:User')->find($userId);

        if (!empty($userInfo))
        {
            dump($userInfo);
        }
        dump($userInfo);
        return $this->render('profile/editProfile.html.twig', [
            'controller_name' => 'ProfileController',
            'firstName' => $userInfo->getFirstName(),
            'lastName' => $userInfo->getlastName(),
        ]);
    }
}
