<?php

namespace App\Profile;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileUploader
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function uploadImage($photo, $uploads, $path)
    {
        $uploads_directory = $this->params->get($uploads);
        $photoName = md5(\uniqid()) . '.' . $photo->guessExtension();
        $photo->move($uploads_directory, $photoName);
        $photoName = $path . $photoName;

        return $photoName;
    }
}
