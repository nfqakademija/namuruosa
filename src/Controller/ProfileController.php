<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id<\d+>}", name="profile") ///
     */
    public function index($id=null)
    {
        $user = $this->getUser();
        if($id === null)
        {
            $userId = $user->getId();
        }else{
            $userId = $id;
            $user = $this->getDoctrine()->getRepository('App:User')->find($userId);
        }
        $userInfo = $this->getDoctrine()->getRepository('App:UserProfile')->find($userId);
        if ($userInfo){
            $firstName = $userInfo->getUserId()->getFirstName();
            $lastName = $userInfo->getUserId()->getlastName();
            $time = $userInfo->getUserId()->getLastLogin();
            $userCity = $userInfo->getCity();
            $description = $userInfo->getDescription();

        }else{
            $firstName = $user->getFirstName();
            $lastName = $user->getlastName();
            $time = $user->getLastLogin();
            $userCity = '';
            $description = '';
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'firstName' => $firstName,
            'lastName' => $lastName,
            'city' => $userCity,
            'time' => $time,
            'description' => $description
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
