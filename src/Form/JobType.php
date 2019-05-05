<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 13.15
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'=>'Pavadinimas',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Aprašymas',
                'required' => true,
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label'=>'Atlikimo terminas',
//                'widget' => 'choice', 'minutes' => [
//                    0,15,30,45
//                ],
            ])
            ->add('category1', ChoiceType::class, [
                'label' => 'Kategorija 1',
                'choices' => [
                    'Žemės ūkis' => 'farming',
                    'Mokymas' => 'education',
                ],
                'required' => false,
            ])
            ->add('category2', ChoiceType::class, [
                'label' => 'Kategorija 2',
                'choices' => [
                    'Sodininkystė' => 'orchard',
                    'Vaikų prežiūra' => 'children',
                ],
                'required' => false,
            ])
            ->add('upload', FileType::class, [
                'label' => 'Objekto foto',
                'required' => false,
            ])
            ->add('payType', ChoiceType::class, [
                'label' => 'Apmokėjimas',
                'choices' => [
                    'Už valandą' => 'per_hour',
                    'Už visą darbą' => 'per_job',
                ]
            ])
            ->add('budget', NumberType::class, [
                'label' => 'Suma EUR',
            ])
            ->add('city', TextType::class, [
                'label'=>'Miestas',
                'mapped' => false,
                'required' => true,
            ])
            ->add('street', TextType::class, [
                'label'=>'Gatvė',
                'mapped' => false,
                'required' => true,
            ])
            ->add('houseNo', TextType::class, [
                'label'=>'Namo Nr',
                'mapped' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Job'
        ]);
    }

}