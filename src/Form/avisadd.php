<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;  
use Symfony\Component\OptionsResolver\OptionsResolver;

class avisadd extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('note', ChoiceType::class, [
            'choices' => [
                '★' => '1',
                '★★' => '2',
                '★★★' => '3',
                '★★★★' => '4',
                '★★★★★' => '5',
            ],
            'label' => 'Note',
            'expanded' => true, // Utilisez ce paramètre pour afficher les choix sous forme de boutons radio
            'multiple' => false, // Définissez à false pour ne permettre qu'une seule sélection
            // Vous pouvez également spécifier des attributs HTML supplémentaires ici
        ])
            ->add('commentaire')
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
