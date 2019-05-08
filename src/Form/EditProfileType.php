<?php

namespace App\Form;

use App\Entity\UserProfile;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'label' => 'Darbas',
                'required' => true,
                ])
            ->add('photo', FileType::class, [
                'label' => 'Nuotrauka',
                'required' => true,
                ])
            ->add('city', TextType::class, [
                'label' => 'Miestas',
                'required' => true,
                ])
            ->add('description', TextareaType::class, [
                'label' => 'Aprasymas',
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
