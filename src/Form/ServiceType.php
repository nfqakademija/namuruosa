<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 13.15
 */

namespace App\Form;


use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('serviceType', ChoiceType::class, [
//
//                'label' => ' ',
//                'label_attr' => array(
//                    'class' => 'radio-inline'
//                ),
//                'expanded' => true,
//                'choices' => [
//                    'Ieškau paslaugos' => 'customer', 'Siūlau paslaugą' => 'provider',
//                ]
//            ])
            ->add('title', TextType::class, [
                'label' => 'Pavadinimas',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Aprašymas',
                'required' => true,
            ])
            ->add('city', TextType::class, [
                'label' => 'Miestas',
                'mapped' => false,
                'required' => true,
            ])
            ->add('street', TextType::class, [
                'label' => 'Gatvė',
                'mapped' => false,
                'required' => true,
            ])
            ->add('houseNo', TextType::class, [
                'label' => 'Namo Nr',
                'mapped' => false,
                'required' => true,
            ])
            ->add('category', EntityType::class, [
                'label' => 'Kategorijos',
                'class' => 'App:Category',
                'choice_label' => function (Category $category) {
                    return $category->getName();
                },
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('maxDistance', IntegerType::class, [
                'label' => 'Kaip toli galite vykti (Km)'
            ])
            ->add('pricePerHour', NumberType::class, [
                'label' => 'Valandos kaina (Eur)',
            ])
            ->add('activeTill', DateTimeType::class, [
                'label' => 'Skelbimas galioja iki',
//                'widget' => 'choice', 'minutes' => [
//                    0, 15, 30, 45
//                ],
            ])
            ->add('lat', NumberType::class, [
                'label' => 'Latitude',
            ])
            ->add('lon', NumberType::class, [
                'label' => 'Longitude',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Service'
        ]);
    }

}
