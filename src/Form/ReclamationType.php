<?php

namespace App\Form;


use App\Entity\Reclamation;
use App\Entity\File;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $reclamation=$options['data'];
        $builder
        ->add('libelle',ChoiceType::class, [
            'choices' => [
                'Auto' => 'auto',
                'Home' => 'Home',
                'Pet' => 'pet',
            ],
            'required' => true,
            'label' => 'Field Label',
            'attr' => [
                'class' => 'form-control',
            ],
        ])
         

      
        ->add('date_sin', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true,
        ])
            ->add('contenu_rec')
        
            ->add('file', FileType::class, [
                'label' => 'Fichier à importer',
                'required' => false, // Rendre le champ facultatif
                'mapped' => false, // Ne pas lier le champ à une propriété de l'entité Reclamation
            ]);
    }
}
