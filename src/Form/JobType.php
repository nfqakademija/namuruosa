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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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
                'label' => 'Pavadinimas',
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Aprašymas',
                'required' => true,
            ])
            ->add('dateEnd', DateType::class, [
                'label' => 'Atlikimo terminas',
                'widget' => 'single_text',
                'attr' => ['value' => date('Y-m-d')],
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
            ->add('upload', FileType::class, [
                'label' => 'Objekto foto',
                'required' => false,
            ])
            ->add('address', SearchType::class, [
                'label' => 'Adresas'
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
            ->add('lat', HiddenType::class, [
                'label' => 'Latitude',
            ])
            ->add('lon', HiddenType::class, [
                'label' => 'Longitude',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Job'
        ]);
    }
}
