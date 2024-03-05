<?php

namespace App\Form;


use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
        ->add('libelle')
         

      
        ->add('date_sin', DateTimeType::class, [
            'widget' => 'single_text',
            'html5' => true,
            
            
        ])
        ->add('contenuRec', TextareaType::class, [
            'label' => 'Contenu',
            'attr' => [
                'class' => 'form-control',
                'rows' => 5, 
                'style' => 'overflow: hidden;',
            ],
        ])
            ->add('file', FileType::class, [
                'label' => 'Fichier à importer',
                'required' => false, 
                'mapped' => false, 
            ])
            ->add('latitude')
            ->add('longitude')
;
    }
}
