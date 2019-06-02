<?php

namespace App\Form;

use App\Entity\Reviews;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Brokoskokoli\StarRatingBundle\Form\StarRatingType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Validator\Constraints\Length;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'comment',
                 'constraints' => [new Length(['min' => 20])]
            ])
            ->add('rating', StarRatingType::class, [
    	         'label' => 'Rating'
    ])

            ->add('recaptcha', EWZRecaptchaType::class, [
              'required' => true,
              'mapped' => false
            ]);
  }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reviews::class,
        ]);
    }
}
