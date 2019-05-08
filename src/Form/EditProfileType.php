<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('job_title', TextType::class, [
                'required' => true,
                ])
            ->add('photo', FileType::class, [
                'required' => true,
                ])
            ->add('city', TextType::class, [
                'required' => true,
                ])
            ->add('languages', TextType::class, [
                'required' => true,
                ])
            ->add('skill', TextType::class, [
                'required' => true,
                ])
            ->add('hour_price', IntegerType::class, [
                'required' => true,

                ])
            ->add('phone', TextType::class, [
                'required' => true,

                ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserProfile::class,
        ]);
    }
}
