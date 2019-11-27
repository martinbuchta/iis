<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Jméno',
            ])
            ->add('surname', null, [
                'label' => 'Příjmení',
            ])
            ->add('email')
            ->add('role', ChoiceType::class, [
                'label' => 'Role',
                'choices' => [
                    'Administrátor' => 'ROLE_ADMINISTRATOR',
                    'Redaktor' => 'ROLE_REDAKTOR',
                    'Pokladní' => 'ROLE_POKLADNI',
                    'Divák' => 'ROLE_DIVAK',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
