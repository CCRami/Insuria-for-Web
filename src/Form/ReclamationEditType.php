<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ReclamationEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
         ->add('reponse', ChoiceType::class, [
                'choices' => [
                    'Currently being processed' => 'Currently being processed',
                    'refused' => 'refused',
                    'accepted' => 'accepted',
                ],
                'required' => true,
                'label' => 'Field Label',
                'attr' => [
                    'class' => 'form-control',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
