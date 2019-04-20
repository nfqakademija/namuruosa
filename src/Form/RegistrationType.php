<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, [
            'label' => 'form.firstName',
            'translation_domain' => 'FOSUserBundle',
            'attr'=>[
                'class'=>'form-control',
            ]
        ]);
        $builder->add('lastName', TextType::class, [
            'label' => 'form.lastName',
            'translation_domain' => 'FOSUserBundle'
        ]);
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}