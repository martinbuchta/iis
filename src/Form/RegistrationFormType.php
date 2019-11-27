<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
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
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Hesla se musejí shodovat.',
                'required' => true,
                'first_options' => ['label' => 'Zadejte heslo'],
                'second_options' => ['label' => 'Zopakujte heslo'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Prosím zadejte heslo.',
                    ]),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Heslo by mělo mít alespon {{ limit }} znaků.',
                        'max' => 4096,
                    ]),
                ],
            ])
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
