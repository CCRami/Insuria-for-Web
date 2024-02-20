<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType
use App\Entity\Assurance; // Import Assurance entity
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['doa_values'] as $index => $value) {
            $builder->add('doa_' . $index, TextType::class, [
                'label' => $value , // Assuming you want a label for each field
                'data' => null, // Set the default value of the field
                'mapped' => false,
                // Add any other options you need for the input field
            ]);
        }
    }

        
        public function configureOptions(OptionsResolver $resolver): void
        {
            
                //$resolver->setDefaults([
                //'data_class' => Commande::class,
                //'assurance_doas' => [],
                //'assurance' => null,
           // ]);
           $resolver->setDefaults([
            // Pass doa values as an option to the form type
            'data_class' => Commande::class,
            'doa_values' => [],
            'assurance' => null,
        ]);
        
        }
}
