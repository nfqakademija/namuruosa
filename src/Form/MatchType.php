<?php

namespace App\Form;

use App\Entity\Matches;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('createdAt')
            ->add('acceptedAt')
            ->add('rejectedAt')
            ->add('cancelledAt')
            ->add('payedAt')
            ->add('callerId')
//            ->add('callerServiceId')
            ->add('responderId')
//            ->add('responderServiceId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Matches::class,
        ]);
    }
}
