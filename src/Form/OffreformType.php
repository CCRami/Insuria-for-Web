<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\categorieOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class OffreformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('advantage')
            ->add('conditions')
            ->add('duration')
            ->add('discount')
            // Add the category field
            ->add('categorie', EntityType::class, [
                'class' => CategorieOffre::class,
                'choice_label' => 'categorie_name', // Adjust this according to your Categorie entity property
                'placeholder' => 'Choose a categorieOffre', // Optional placeholder
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
