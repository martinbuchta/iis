<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user', null, [
                'label' => 'Uživatel',
                'placeholder' => '-- Bez registrace --',
            ])
            ->add('name', null, [
                'label' => 'Jméno',
            ])
            ->add('surname', null, [
                'label' => 'Příjmení',
            ])
            ->add('email')
            ->add('paid', null, [
                'label' => 'Zaplaceno',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
