<?php

namespace App\Form;

use App\Entity\Performance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PerformanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('play', null, [
                'label' => 'Inscenace',
                'required' => true,
            ])
            ->add('hall', null, [
                'label' => 'Sál',
            ])
            ->add('time', DateTimeType::class, [
                'label' => 'Čas',
                'html5' => true,
                'widget' => 'single_text',
                'with_seconds' => false,
            ])
            ->add('price', null, [
                'label' => 'Cena za vstupenku',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Performance::class,
        ]);
    }
}
