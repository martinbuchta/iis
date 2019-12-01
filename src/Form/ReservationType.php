<?php

namespace App\Form;

use App\Entity\Performance;
use App\Entity\Reservation;
use App\Entity\Seat;
use App\Repository\PerformanceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReservationType extends AbstractType
{
    /**
     * @var PerformanceRepository
     */
    private $performanceRepository;

    public function __construct(PerformanceRepository $performanceRepository)
    {
        $this->performanceRepository = $performanceRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['anonymous']) {
            $builder
                ->add('name', null, [
                    'label' => 'Jméno',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ])
                ->add('surname', null, [
                    'label' => 'Příjmení',
                    'constraints' => [
                        new NotBlank(),
                    ],
                ])
                ->add('email', EmailType::class, [
                    'label' => 'Email',
                    'constraints' => [
                        new NotBlank(),
                        new Email(),
                    ],
                ])
                ->add('plainPassword', RepeatedType::class, [
                    'mapped' => false,
                    'type' => PasswordType::class,
                    'required' => false,
                    'invalid_message' => 'Hesla se musejí shodovat.',
                    'first_options' => ['label' => 'Heslo (nezadávejte, pokud chcete pokračovat bez vytvoření účtu)'],
                    'second_options' => ['label' => 'Zopakujte heslo (nezadávejte, pokud chcete pokračovat bez vytvoření účtu)'],
                    'constraints' => [
                        new Length([
                            'min' => 5,
                            'minMessage' => 'Heslo by mělo mít alespon {{ limit }} znaků.',
                            'max' => 4096,
                        ]),
                    ],
                ])
            ;
        }

        /** @var Performance $performance */
        $performance = $options['performance'];

        $availableSeats = [];

        foreach ($performance->getHall()->getSeats() as $seat) {
            $availableSeats[] = $seat;
        }

        $builder->add('seats', ChoiceType::class, [
            'mapped' => false,
            'choices' => $availableSeats,
            'choice_attr' => function (Seat $seat) {
                return ['class' => 'seat' . $seat->getId()];
            },
            'multiple' => true,
            'expanded' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['performance', 'anonymous']);
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
