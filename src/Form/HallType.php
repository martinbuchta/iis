<?php

namespace App\Form;

use App\Entity\Hall;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HallType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Název'])
            ->add('address', null, ['label' => 'Adresa'])
            ->add('rowCount', null, ['label' => 'Počet řad'])
            ->add('seatsInRow', null, ['label' => 'Sedadel v řadě'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Hall::class,
        ]);
    }
}
