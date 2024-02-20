<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class avisadd extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note')
            ->add('commentaire')
            ->add('avis', EntityType::class, [
                'class' => 'App\Entity\User', // Replace with the actual namespace of your Author entity
                'choice_label' => 'email', // Assuming Author entity has a method getFullName() that returns the author's full name
                'placeholder' => 'Select an author', // Optional, adds an empty option at the top
                'required' => true, // Set to true if the author selection is mandatory
    
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
