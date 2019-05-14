<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Entity\UserProfile;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {
        $user = $this->getUser();

        $profile = $user->getUserProfile();

        if (!$profile){
            $profile = new UserProfile;
            $profile->setCity('');
            $profile->setJobTitle('');
            $profile->setDescription('');
            $profile->setLanguages('');
            $profile->setSkill('igudis1, igudis2');
            $profile->setPhoto('profile-icon.png');
            $profile->setHourPrice(0);

      }
        return $this->render('profile/logedUserProfile.html.twig', [
            'user' => $user,
            'profile' => $profile,
            'controller_name' => 'ProfileController',
            ]);
    }

    /**
     * @Route("/profile/user/{id}", name="otherUserProfile"), requirements={"id"="\d+"}
     */

    public function otherUserProfile($id)
    {
      $profile = $this->getDoctrine()->getRepository('App:UserProfile')->
      findBy(['id' => $id])[0];
      $user = $profile->getUserId();

      return $this->render('profile/otherUserProfile.html.twig', [
          'user' => $user,
          'profile' => $profile,
          'id' => $id,
          'controller_name' => 'ProfileController',
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
            'profile' => $userInfo
        ]);
    }
    /**
     * @Route("/profile/review/{id}", name="reviewUser", requirements={"id"="\d+"})
     */

    public function reviewProfile()
    {
      return $this->render('profile/rateUser.html.twig');
    }
}
