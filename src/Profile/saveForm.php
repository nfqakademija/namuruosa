<?php

namespace App\Profile;

use App\Profile\fileUploader;
use Symfony\Component\HttpFoundation\Request;

class saveForm
{
  // public $request;
  //
  // public function __construct(Request $request){
  //   $this->request = $request;
  // }

  public function saveForm($form, $userProfile, $request){

    $entityManager = $this->getDoctrine()->getManager();
    $formData = $form->getData();
    dump($request);
    $file = $request->files->get('edit_profile')['profile_photo'];

    if ($file) {

      $uploads_directory = $this->getParameter('profile_pics_dir');

      $fileName = md5(\uniqid()) . '.' . $file->guessExtension();

      $file->move($uploads_directory, $fileName);

    }else {
      $fileName = 'profile-icon.png';
    }


    if (!$userProfile)
    {

        $profile->setUserId($userObj);
        $profile->setPhoto($fileName);

        $entityManager->persist($formData);
        $entityManager->flush();


        $this->addFlash(
          'notice',
          'Jūsų profilis sukurtas!'
        );
    }else
    {
        $userProfile->setCity($form["city"]->getData());
        $userProfile->setLanguages($form["languages"]->getData());
        $userProfile->setSkill($form["skill"]->getData());
        $userProfile->setPhone($form["phone"]->getData());
        $userProfile->setHourPrice($form["hour_price"]->getData());
        $userProfile->setDescription($form["description"]->getData());
        $userProfile->setPhoto($fileName);
        $entityManager->persist($userProfile);
        $entityManager->flush();

        $this->addFlash(
          'notice',
          'Jūsų profilis atnaujintas!'
        );
    }

    return $this->redirectToRoute('profile');
  }




}
