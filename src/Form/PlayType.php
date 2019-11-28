<?php

namespace App\Form;

use App\Entity\Play;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PlayType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Název'])
            ->add('staring', null, ['label' => 'Obsazení', 'attr' => ['class' => 'ckeditor']])
            ->add('rating', null, ['label' => 'Hodnocení (1-5)'])
            ->add('image', FileType::class, [
                'label' => 'Obrázek',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new Image()
                ],
            ])
            ->add('category', null, [
                'label' => 'Kategorie',
                'placeholder' => 'Vyberte kategorii',
            ])
            ->add('genres', null, [
                'label' => 'Žánry',
                'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Play::class,
        ]);
    }
}
