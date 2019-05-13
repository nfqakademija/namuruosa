<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile/{id}", name="profile", requirements={"page"="\d+"})
     */
    public function index($id = 0)
    {
        $user = $this->getUser();

        $profile = $user->getUserProfile();

        if ($id !== 0) {
            $profile = $this->getDoctrine()->getRepository('App:UserProfile')->
            findBy(['id' => $id])[0];
            $user = $profile->getUserId();
        }

        if ($profile){
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            $time = $user->getLastLogin();
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
            $photo = 'profile-icon.png';
            $price = '';
        }
        return $this->render('profile/index.html.twig', [
            'profile' => $profile,
            'controller_name' => 'ProfileController',
            'firstName' => $firstName,
            'lastName' => $lastName,
            'city' => $userCity,
            'time' => $time,
            'description' => $description,
            'languages' => $langs,
            'title' => $title,
            'skill' => $skills,
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

        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $profile = $form->getData();
            $file = $request->files->get('edit_profile')['photo'];

            if ($file) {

              $uploads_directory = $this->getParameter('profile_pics_dir');

              $fileName = md5(\uniqid()) . '.' . $file->guessExtension();

              $file->move($uploads_directory, $fileName);

            }else {
              $fileName = 'profile-icon.png';
            }


            if (!$userInfo)
            {

                $profile->setUserId($userObj);
                $profile->setPhoto($fileName);

                $entityManager->persist($profile);
                $entityManager->flush();
            }else
            {
                $userInfo->setCity($form["city"]->getData());
                $userInfo->setDescription($form["description"]->getData());
                $userInfo->setPhoto($fileName);
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
