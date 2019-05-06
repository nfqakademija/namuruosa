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
            ->add('serviceType', ChoiceType::class, [

                'label' => ' ',
                'label_attr' => array(
                    'class' => 'radio-inline'
                ),
                'expanded'=>true,
                'choices' => [
                   'Ieškau paslaugos'=>'customer', 'Siūlau paslaugą'=>'provider',
                ]
            ])
            ->add('title', TextType::class, [
                'label'=>'Pavadinimas',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label'=>'Aprašymas',
                'required' => true,
            ])
            ->add('activeTimeStart', TimeType::class, [
                'label'=>'Paslaugos pradžia',
                'widget' => 'choice', 'minutes' => [
                    0,15,30,45
                ],
            ])
            ->add('activeTimeEnd', TimeType::class, [
                'label'=>'Paslaugos pabaiga',
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
                'label' => 'Koordinatė X',
                'choices' => [
                    range(1, 100, 5)
                ]
            ])
            ->add('coordinateY', ChoiceType::class, [
                'label' => 'Koordinatė Y',
                'choices' => [
                    range(1, 100, 5)
                ]
            ])
            ->add('category', EntityType::class, [
                    'label' => 'Kategorijos',
                    'class' => 'App:Category',
//                        'homeCare' => 'pavadinimas',
                    'choice_label' => function(Category $category){
                        return $category->getName();
                    },
//                    'property' => 'name',
                    'multiple' => true,
                    'expanded' => true,
                ]

            )

//            ->add('category', ChoiceType::class, [
//                'label' => 'Kategorijos',
//                'choices' => [
//                    'Mokymas' => 'education',
//                    'Vaiku prieziura' => 'kidCare',
//                    'Transportas' => 'transport',
//                    'Valymas' => 'houseCare',
//                    'Automobilio remontas' => 'autoCare',
//                    'Namu meistras' => 'houseMaster'
//                ],
//                'expanded' => true,
//                'multiple' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Service'
        ]);
    }

}
