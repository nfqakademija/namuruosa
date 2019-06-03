<?php

namespace App\Controller;

use App\Entity\Reports;
use App\Entity\User;
use App\Form\ReportType;
use App\Service\ReportCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\EditProfileType;
use App\Form\ReviewType;
use App\Entity\UserProfile;
use App\Profile\DataLoader;
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
            $profile = $this->setNewProfile($user);
        }
        return $this->render('profile/logedUserProfile.html.twig', [
            'user' => $user,
            'profile' => $profile,
            'reviews' => $reviews,
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

        $reportForm = $this->createForm(ReportType::class, $report = new Reports())
            ->handleRequest($request);

        if ($reportForm->isSubmitted() && $reportForm->isValid()) {
            $report->setCreatedAt(new \DateTime())
                ->setReportedUserId($userId)
                ->setReporterUserId($this->getUser()->getId());
            $report = $reportForm->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($report);
            $em->flush();
        }
        return $this->render('profile/otherUserProfile.html.twig', [
            'user' => $user,
            'profile' => $profile,
            'userId' => $userId,
            'services' => $userServices,
            'jobs' => $userJobs,
            'money' => $money,
            'reviews' => $reviews,
            'rating' => $rating,
            'reviewsCount' => $totalReviews,
            'controller_name' => 'ProfileController',
            'reportForm' => $reportForm->createView(),
        ]);
    }

    /**
     * @Route("/profile/edit", name="editProfile")
     */
    public function editProfile(Request $request, FileUploader $uploader)
    {

        $userObj = $this->getUser();
        $userProfile = $userObj->getUserProfile();

        $form = $this->createForm(EditProfileType::class, $userProfile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->saveProfileForm($form, $userProfile, $uploader, $userObj, $request);

            $this->addFlash(
                'notice',
                'Jūsų profilis atnaujintas!'
            );

            return $this->redirectToRoute('profile');

        } elseif (!$form->isSubmitted()) {
            $form->get('description')->setData(
                $userProfile->getdescription());

        }

        return $this->render('profile/editProfileForm.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function saveProfileForm($form, $userProfile, $uploader, $userObj, $request)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $formData = $form->getData();

        $profilePhoto = $request->files->get('edit_profile')['profilePhoto'];
        $bannerPhoto = $request->files->get('edit_profile')['bannerPhoto'];

        $profilePhotoName = 'build/images/profile-icon.png';

        if ($profilePhoto) {
            $profilePhotoName = $uploader->uploadImage($profilePhoto, 'profile_pics_dir', 'uploads/profile_pics/');
        }

        $bannerPhotoName = 'build/images/cooperation.jpg';

        if ($bannerPhoto) {
            $bannerPhotoName = $uploader->uploadImage($bannerPhoto, 'banner_pics_dir', 'uploads/banner_pics/');

        }

        if (!$userProfile) {
            $userProfile->setUserId($userObj);
            $userProfile->setProfilePhoto($profilePhotoName);
            $userProfile->setBannerPhoto($abnnerPhotoName);

            $entityManager->persist($formData);
            $entityManager->flush();

        } else {
            $userProfile->setCity($form["city"]->getData());
            $userProfile->setLanguages($form["languages"]->getData());
            $userProfile->setSkill($form["skill"]->getData());
            $userProfile->setPhone($form["phone"]->getData());
            $userProfile->setDescription($form["description"]->getData());
            if ($profilePhoto) {
                $userProfile->setProfilePhoto($profilePhotoName);
            }

            if ($bannerPhoto) {
                $userProfile->setBannerPhoto($bannerPhotoName);
            }
            $entityManager->flush();
        }
    }

    public function setNewProfile($user)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $profile = new UserProfile;
        $profile->setUserId($user);
        $profile->setCity('');
        $profile->setDescription('');
        $profile->setLanguages('');
        $profile->setSkill('igudis1, igudis2');
        $profile->setProfilePhoto('build/images/profile-icon.png');
        $profile->setBannerPhoto('build/images/cooperation.jpg');
        $profile->setPhone('');
        $entityManager->persist($profile);
        $entityManager->flush();

        return $profile;
    }

}
