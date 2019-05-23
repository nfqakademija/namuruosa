<?php

namespace App\Profile;

use App\Profile\fileUploader;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class saveForm
{
  private $entityManager;
  protected $requestStack;

  public function __construct(EntityManagerInterface $manager, RequestStack $request){
    $this->entityManager = $manager;
    $this->requestStack =  $request;
  }

  public function saveProfileForm($form, $userProfile, $uploader){

    $formData = $form->getData();

    $profilePhoto = $this->requestStack->getCurrentRequest()->files->get('edit_profile')['profilePhoto'];
    $bannerPhoto = $this->requestStack->getCurrentRequest()->files->get('edit_profile')['bannerPhoto'];

    if ($profilePhoto) {
        $profilePhotoName = $uploader->uploadImage($profilePhoto, 'profile_pics_dir');

    }else {
        $profilePhotoName = 'profile-icon.png';
    }

    if ($bannerPhoto) {
        $bannerPhotoName = $uploader->uploadImage($bannerPhoto, 'banner_pics_dir');

    }else {
        $bannerPhototoName = 'chore.jpg';
    }

    if (!$userProfile)
    {

        $profile->setUserId($userObj);
        $profile->setPhoto($profilePhotoName);

        $this->entityManager->persist($formData);
        $this->entityManager->flush();

    }else
    {
        $userProfile->setCity($form["city"]->getData());
        $userProfile->setLanguages($form["languages"]->getData());
        $userProfile->setSkill($form["skill"]->getData());
        $userProfile->setPhone($form["phone"]->getData());
        $userProfile->setDescription($form["description"]->getData());
        $userProfile->setProfilePhoto($profilePhotoName);
        $userProfile->setBannerPhoto($bannerPhotoName);
        $this->entityManager->persist($userProfile);
        $this->entityManager->flush();

    }

  }

}
