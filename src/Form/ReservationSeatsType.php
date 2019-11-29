<?php

namespace App\Form;

use App\Entity\Performance;
use App\Entity\Seat;
use App\Repository\PerformanceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationSeatsType extends AbstractType
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
        /** @var Performance $performance */
        $performance = $options['performance'];

        $orderedSeats = [];
        foreach ($performance->getTickets() as $ticket) {
            $orderedSeats[] = $ticket->getSeat()->getId();
        }

        foreach ($performance->getHall()->getSeats() as $seat) {
            if (in_array($seat->getId(), $orderedSeats)) {
                continue;
            }

            $builder->add('seat' . $seat->getId(), CheckboxType::class, [
                'required' => false,
                'label' => $seat->getId(),
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('performance');
    }
}
