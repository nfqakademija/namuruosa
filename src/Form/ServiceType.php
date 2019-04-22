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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextareaType::class, [
                'required'=> true,
            ])
            ->add('activeTimeStart', DateTimeType::class, [
                'input'  => 'timestamp',
                'widget' => 'choice',
            ] )
            ->add('activeTimeEnd', DateTimeType::class, [
                'input'  => 'timestamp',
                'widget' => 'choice',
            ] )
            ->add('transport', CheckboxType::class, [
                'label'=> 'Transporto paslaugos',
            ])
            ->add('education', CheckboxType::class, [
                'label'=> 'Mokymo paslaugos'
            ])
            ->add('cleaning', CheckboxType::class, [
                'label'=> 'Valymo paslaugos'
            ])
            ->add('coordinateX', ChoiceType::class, [
                'choices'=>[
                    range(1, 100, 5)
                ]
            ])
            ->add('coordinateY', ChoiceType::class, [
                'choices'=>[
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