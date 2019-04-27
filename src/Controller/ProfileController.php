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
        $userInfo = $this->getDoctrine()->getRepository('App:UserProfile')->find($userId);


        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'firstName' => $userInfo->getUserId()->getFirstName(),
            'lastName' => $userInfo->getUserId()->getlastName(),
            'city' => $userInfo->getCity(),
            'time' => $userInfo->getUserId()->getLastLogin(),
            'description' => $userInfo->getDescription()
            ]);
    }
    /**
     * @Route("/profile/edit", name="editProfile")
     */
    public function editProfile(Request $request)
    {
        $form = $this->createForm(EditProfileType::class);
        $form->handleRequest($request);
        $userObj = $this->getUser();
        $userId = $userObj->getId();

        $userInfo = $this->getDoctrine()->getRepository('App:UserProfile')->find($userId);


        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $profile = $form->getData();
            dump($form->getData());


            if (!$userInfo)
            {

                $profile->setUserId($userObj);

                $entityManager->persist($profile);
                $entityManager->flush();
            }else
            {
                $userInfo->setCity($form["city"]->getData());
                $userInfo->setDescription($form["description"]->getData());
                $entityManager->persist($userInfo);
                $entityManager->flush();

            }

            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/editProfile.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}
