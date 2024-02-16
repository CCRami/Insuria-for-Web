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
            ->add('doa')
         ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Assurance::class,
        ]);
    }
}
