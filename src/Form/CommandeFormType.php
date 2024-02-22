<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;


class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        foreach ($options['doa_values'] as $index => $value) {
            $builder->add('doa_' . $index, TextType::class, [
                'label' => $value , 
                'data' => null, 
                'mapped' => false,
                
            ]);
        }

        $builder->add('insvalue')
                ;
    }

        
        public function configureOptions(OptionsResolver $resolver): void
        {
            
           
           $resolver->setDefaults([
            
            'data_class' => Commande::class,
            'doa_values' => [],
            'assurance' => null,
        ]);
        
        }
}
