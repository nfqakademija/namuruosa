<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        $userId = $this->getUser()->getId();
        $userInfo = $this->getDoctrine()->getRepository('App:User')->find($userId);
        $moreInfo = $this->getDoctrine()->getRepository('App:UserProfile')->find($userId);

        dump($moreInfo);

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'firstName' => $userInfo->getFirstName(),
            'lastName' => $userInfo->getlastName(),
        ]);
    }
    /**
     * @Route("/profile/edit", name="editProfile")
     */
    public function editProfile(Request $request)
    {
        $form = $this->createForm(EditProfileType::class);
        $form->handleRequest($request);
        $userId = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();

            $profile = $form->getData();
            $profile->setUserId($userId);

            $entityManager->persist($profile);
            $entityManager->flush();
            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/editProfile.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
