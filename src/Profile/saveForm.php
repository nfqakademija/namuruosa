<?php

namespace App\Profile;

use App\Profile\fileUploader;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class saveForm
{
    private $entityManager;
    protected $request;

    public function __construct(EntityManagerInterface $manager, RequestStack $requestStack)
    {
        $this->entityManager = $manager;
        $this->request = $requestStack;
    }

  public function saveProfileForm($form, $userProfile, $uploader, $userObj){

    $formData = $form->getData();

    $profilePhoto = $this->request->getCurrentRequest()->files->get('edit_profile')['profilePhoto'];
    $bannerPhoto = $this->request->getCurrentRequest()->files->get('edit_profile')['bannerPhoto'];

    if ($profilePhoto) {
        $profilePhotoName = $uploader->uploadImage($profilePhoto, 'profile_pics_dir', 'uploads/profile_pics/');

    }else {
        $profilePhotoName = 'build/images/profile-icon.png';
    }

    if ($bannerPhoto) {
        $bannerPhotoName = $uploader->uploadImage($bannerPhoto, 'banner_pics_dir', 'uploads/banner_pics/');

    }else {
        $bannerPhotoName = 'build/images/chore.jpg';
    }

    if (!$userProfile)
    {
        $userProfile->setUserId($userObj);
        $userProfile->setPhoto($profilePhotoName);

        $this->entityManager->persist($formData);
        $this->entityManager->flush();

    }else
    {
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
        $this->entityManager->flush();

    }

  }

}
