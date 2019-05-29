<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Form\ReviewType;
use App\Entity\UserProfile;
use App\Profile\DataLoader;
use App\Profile\SaveForm;
use App\Profile\FileUploader;

class ProfileController extends AbstractController
{

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(DataLoader $dataLoader)
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $profile = $user->getUserProfile();
        $entityManager = $this->getDoctrine()->getManager();

        $reviews = $dataLoader->getAllReviews($userId);
        $totalReviews = $dataLoader->getCountReviews($userId);
        $rating = $dataLoader->getAverageRating($userId);
        $userServices = $dataLoader->countUserServices($userId);
        $userJobs = $dataLoader->countUserJobs($userId);
        $money = $dataLoader->countUserMoney($userId);

        if (!$profile) {
            $profile = new UserProfile;
            $profile->setUserId($user);
            $profile->setCity('');
            $profile->setDescription('');
            $profile->setLanguages('');
            $profile->setSkill('igudis1, igudis2');
            $profile->setProfilePhoto('build/images/profile-icon.png');
            $profile->setBannerPhoto('build/images/chores.jpg');
            $profile->setPhone('');
            $entityManager->persist($profile);
            $entityManager->flush();
        }
        return $this->render('profile/logedUserProfile.html.twig', [
            'user' => $user,
            'profile' => $profile,
            'reviews' =>$reviews,
            'services' => $userServices,
            'jobs' => $userJobs,
            'money' => $money,
            'rating' => $rating,
            'reviewsCount' => $totalReviews,
            'controller_name' => 'ProfileController',
        ]);
    }

    /**
     * @Route("/profile/user/{userId}", name="otherUserProfile"), requirements={"userId"="\d+"}
     */

    public function otherUserProfile($userId, DataLoader $dataLoader, Request $request)
    {
      $profile = $this->getDoctrine()->getRepository(UserProfile::class)->
      findOneBy(['user_id' => $userId]);

      $user = $profile->getUserId();

      $reviews = $dataLoader->getAllReviews($userId, $request);
      $totalReviews = $dataLoader->getCountReviews($userId);
      $rating = $dataLoader->getAverageRating($userId);
      $userServices = $dataLoader->countUserServices($userId);
      $userJobs = $dataLoader->countUserJobs($userId);
      $money = $dataLoader->countUserMoney($userId);

      return $this->render('profile/otherUserProfile.html.twig', [
          'user' => $user,
          'profile' => $profile,
          'userId' => $userId,
          'services' => $userServices,
          'jobs' => $userJobs,
          'money' => $money,
          'reviews' => $reviews,
          'rating' => $rating,
          'reviewsCount'=> $totalReviews,
          'controller_name' => 'ProfileController',
          ]);
    }

    /**
     * @Route("/profile/edit", name="editProfile")
     */
    public function editProfile(Request $request, SaveForm $saver, FileUploader $uploader)
    {

        $userObj = $this->getUser();
        $userProfile = $userObj->getUserProfile();

        $form = $this->createForm(EditProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $saver->saveProfileForm($form, $userProfile, $uploader, $userObj);

            $this->addFlash(
                'notice',
                'Jūsų profilis atnaujintas!'
            );

            return $this->redirectToRoute('profile');
        }else {
          // Set default value
          $form->get('description')->setData(
          $userProfile->getdescription()
        );
      }
        return $this->render('profile/editProfileForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/review/{userId}", name="reviewProfile", requirements={"userId"="\d+"})
     */

    public function reviewProfile(Request $request, $userId)
    {
        $form = $this->createForm(ReviewType::class);
        $form->handleRequest($request);

        $estimator = $this->getUser();
        $ratedUser = $this->getDoctrine()->getRepository(User::class)->find($userId);

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

            return $this->redirectToRoute('otherUserProfile', ['userId' => $userId]);
        }

        return $this->render('profile/rateUser.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId
        ]);
    }
}
