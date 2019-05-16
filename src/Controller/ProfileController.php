<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Form\RatingType;
use App\Entity\UserProfile;
use Symfony\Component\Validator\Constraints\DateTime;
use App\Repository\ReviewsRepository;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile(ReviewsRepository $reviewsRepo)
    {
        $user = $this->getUser();

        $profile = $user->getUserProfile();

        $reviews = $reviewsRepo->findAllUserReviews($user->getId());

        $rating = $reviewsRepo->getAverageRating($user->getId());

        dump($rating);
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
            'reviews' =>$reviews,
            'rating' => $rating[0][1],
            'controller_name' => 'ProfileController',
            ]);
    }

    /**
     * @Route("/profile/user/{id}", name="otherUserProfile"), requirements={"id"="\d+"}
     */

    public function otherUserProfile($id)
    {
      $profile = $this->getDoctrine()->getRepository('App:UserProfile')->
      find($id);

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
                $userInfo->setLanguages($form["languages"]->getData());
                $userInfo->setSkill($form["skill"]->getData());
                $userInfo->setPhone($form["phone"]->getData());
                $userInfo->setHourPrice($form["hour_price"]->getData());
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
     * @Route("/profile/review/{id}", name="reviewProfile", requirements={"id"="\d+"})
     */

    public function reviewProfile(Request $request, $id)
    {
      $form = $this->createForm(RatingType::class);
      $form->handleRequest($request);

      $estimator = $this->getUser();
      $ratedUser = $this->getDoctrine()->getRepository('App:UserProfile')->find($id)->getUserId();
      dump($ratedUser);

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
