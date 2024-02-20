<?php

namespace App\Form;

use App\Entity\Police;
use App\Entity\Sinistre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoliceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('police_name')
            ->add('Description_police')
            ->add('sinistre', EntityType::class, [
                'class' => 'App\Entity\Sinistre', // Replace with the actual namespace of your Author entity
                'choice_label' => 'sin_name', // Assuming Author entity has a method getFullName() that returns the author's full name
                'placeholder' => 'Select a Claim', // Optional, adds an empty option at the top
                'required' => true, // Set to true if the author selection is mandatory
    
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Police::class,
        ]);
    }
}
