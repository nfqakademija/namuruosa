<?php

namespace App\Profile;

use App\Profile\fileUploader;

class saveForm
{

  public function saveForm($form, $entityManager, $request, $userProfile){

    $formData = $form->getData();

    $profilePhoto = $request->files->get('edit_profile')['profilePhoto'];
    $bannerPhoto = $request->files->get('edit_profile')['bannerPhoto'];

    $uploader = new fileUploader;

    if ($profilePhoto) {
        $profilePhototoName = $uploader->uploadImage($profilePhoto, 'profile_pics_dir');

    }else {
        $profilePhototoName = 'profile-icon.png';
    }

    if ($bannerPhoto) {
        $bannerPhototoName = $uploader->uploadImage($bannerPhoto, 'profile_pics_dir');

    }else {
        $bannerPhototoName = 'profile-icon.png';
    }


    if (!$userProfile)
    {

        $profile->setUserId($userObj);
        $profile->setPhoto($profilePhotoName);

        $entityManager->persist($formData);
        $entityManager->flush();

    }else
    {
        $userProfile->setCity($form["city"]->getData());
        $userProfile->setLanguages($form["languages"]->getData());
        $userProfile->setSkill($form["skill"]->getData());
        $userProfile->setPhone($form["phone"]->getData());
        $userProfile->setDescription($form["description"]->getData());
        $userProfile->setProfilePhoto($fileName);
        $entityManager->persist($userProfile);
        $entityManager->flush();

    }

  }

}
