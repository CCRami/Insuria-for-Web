<?php

namespace App\Form;

use App\Entity\Police;
use App\Entity\Sinistre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoliceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('police_name')
            ->add('Description_police', TextareaType::class)
            ->add('sinistre', EntityType::class, [
                'class' => 'App\Entity\Sinistre', 
                'choice_label' => 'sin_name', 
                'placeholder' => 'Select a Claim', 
                'required' => true, 
    
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
