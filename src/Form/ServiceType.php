<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 13.15
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('srtviceType', ChoiceType::class, [
                'label' => 'Ieškau paslaugos / siūlau paslaugą',
                'choices' => [
                   'provider', 'customer',
                ]
            ])
            ->add('title', TextType::class, [
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
            ])
            ->add('activeTimeStart', TimeType::class, [
                'widget' => 'choice', 'minutes' => [
                    0,15,30,45
                ],
            ])
            ->add('activeTimeEnd', TimeType::class, [
                'widget' => 'choice', 'minutes' => [
                    0,15,30,45
                ]
            ])
            ->add('transport', CheckboxType::class, [
                'label' => 'Transporto paslaugos',
                'required' => false,
            ])
            ->add('education', CheckboxType::class, [
                'label' => 'Mokymo paslaugos',
                'required' => false,
            ])
            ->add('cleaning', CheckboxType::class, [
                'label' => 'Valymo paslaugos',
                'required' => false,
            ])
            ->add('coordinateX', ChoiceType::class, [
                'choices' => [
                    range(1, 100, 5)
                ]
            ])
            ->add('coordinateY', ChoiceType::class, [
                'choices' => [
                    range(1, 100, 5)
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Service'
        ]);
    }

}
