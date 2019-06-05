<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;


class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', TextType::class, [
                'required' => true,
            ])
            ->add('languages', TextType::class, [
                'required' => true,
            ])
            ->add('skill', TextType::class, [
                'required' => true,
            ])
            ->add('phone', TelType::class, [
                'required' => true,

            ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ])
            ->add('profilePhoto', FileType::class, [
                'required' => false,
                'mapped' => false,
                'data_class' => null,
                'constraints' => [
                    new Image()
                ]
            ])
            ->add('bannerPhoto', FileType::class, [
                'required' => false,
                'mapped' => false,
                'data_class' => null,
                'constraints' => [
                    new Image()
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
