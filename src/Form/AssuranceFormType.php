<?php

namespace App\Form;

use App\Entity\Assurance;
use App\Entity\CategorieAssurance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AssuranceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name_ins')
        ->add('montant')
        ->add('catA', EntityType::class, [
            'class' => CategorieAssurance::class,
            'choice_label' => 'namecatins',
            'placeholder' => 'Choose a category',
            'required' => true,
        ])

        ->add('doa', CollectionType::class, [
            'entry_type' => TextType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
           
            'label' => false, 
        ])
         
       
        ->add('insimage', FileType::class, [
            'label' => 'Insurance Image',
            'required' => false,
            'mapped' => false,
            'attr' => ['accept' => 'image/*']
        ]);

    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}
