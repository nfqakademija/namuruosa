<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Form\RatingType;
use App\Entity\UserProfile;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Profile\Manager;
use App\Profile\saveForm;


class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(Manager $manager, Request $request)
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $profile = $user->getUserProfile();
        $entityManager = $this->getDoctrine()->getManager();

        $reviews = $manager->getAllReviews($userId, $request);
        $totalReviews = $manager->getCountReviews($userId);
        $rating = $manager->getAverageRating($userId);

        if (!$profile){
            $profile = new UserProfile;
            $profile->setCity('');
            $profile->setDescription('');
            $profile->setLanguages('');
            $profile->setSkill('igudis1, igudis2');
            $profile->setProfilePhoto('profile-icon.png');
            $profile->setPhone('profile-icon.png');
            $entityManager->persist($profile);
            $entityManager->flush();

      }
        return $this->render('profile/logedUserProfile.html.twig', [
            'user' => $user,
            'profile' => $profile,
            'reviews' =>$reviews,
            'rating' => $rating[0][1],
            'reviewsCount' => $totalReviews[0][1],
            'controller_name' => 'ProfileController',
            ]);
    }

    /**
     * @Route("/profile/user/{id}", name="otherUserProfile"), requirements={"id"="\d+"}
     */

    public function otherUserProfile($id, Manager $manager, Request $request)
    {
      $profile = $this->getDoctrine()->getRepository(UserProfile::class)->
      find($id);

      $user = $profile->getUserId();
      $userId = $user->getId();

      $reviews = $manager->getAllReviews($userId, $request);
      $totalReviews = $manager->getCountReviews($userId);
      $rating = $manager->getAverageRating($userId);

      return $this->render('profile/otherUserProfile.html.twig', [
          'user' => $user,
          'profile' => $profile,
          'id' => $id,
          'reviews' => $reviews,
          'rating' => $rating[0][1],
          'reviewsCount' => $totalReviews[0][1],
          'controller_name' => 'ProfileController',
          ]);
    }

    /**
     * @Route("/profile/edit", name="editProfile")
     */
    public function editProfile(Request $request, saveForm $saver)
    {
        $form = $this->createForm(EditProfileType::class);
        $form->handleRequest($request);

        $userObj = $this->getUser();
        $userProfile = $userObj->getUserProfile();

        if ($form->isSubmitted() && $form->isValid()){
            $saver->saveForm($form, $userProfile);
        }
        return $this->render('profile/editProfileForm.html.twig', [
            'form' => $form->createView(),
            'profile' => $userProfile
        ]);
    }
    /**
     * @Route("/profile/review/{id}", name="reviewProfile", requirements={"id"="\d+"})
     */

    public function reviewProfile(Request $request, $id)
    {
      $form = $this->createForm(RatingType::class);
      $form->handleRequest($request);

      $estimator = $this->getUser();
      $ratedUser = $this->getDoctrine()->getRepository('App:UserProfile')->find($id)->getUserId();

      if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $review = $form->getData();

        $review->setUserId($ratedUser);
        $review->setEstimatorId($estimator);
        $review->setCreatedAt(new \DateTime());

        $entityManager->persist($review);
        $entityManager->flush();

        $this->addFlash(
          'notice',
          'Jūsų vertinimas išsaugotas!'
        );

        return $this->redirectToRoute('otherUserProfile', ['id' => $id]);
      }

      return $this->render('profile/rateUser.html.twig', [
        'form' => $form->createView(),
        'id' => $id
      ]);
    }
}
