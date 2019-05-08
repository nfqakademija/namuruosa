<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile") ///
     */
    public function index()
    {
        $user = $this->getUser();

        $profile = $user->getUserProfile();
//        \var_dump($profile);

        if ($profile){
            $firstName = $profile->getUserId()->getFirstName();
            $lastName = $profile->getUserId()->getLastName();
            $time = $profile->getUserId()->getLastLogin();
            $userCity = $profile->getCity();
            $description = $profile->getDescription();
            $langs = $profile->getLanguages();
            $skills = \explode(',', $profile->getSkill());
            $title = $profile->getJobTitle();
            $photo = $profile->getPhoto();
            $price = $profile->getHourPrice();

        }else{
            $firstName = $user->getFirstName();
            $lastName = $user->getlastName();
            $time = $user->getLastLogin();
            $userCity = '';
            $description = '';
            $langs = '';
            $title = '';
            $skills = '';
            $photo = 'img/profile-icon.png';
            $price = '';
        }
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'firstName' => $firstName,
            'lastName' => $lastName,
            'city' => $userCity,
            'time' => $time,
            'description' => $description,
            'languages' => $langs,
            'title' => $title,
            'skills' => $skills,
            'photo' => $photo,
            'price' => $price,
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
        $userInfo = $userObj->getUserProfile();
        // $userInfo = $this->getDoctrine()->getRepository('App:UserProfile')->find($userId);

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
        return $this->render('profile/editProfileForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
