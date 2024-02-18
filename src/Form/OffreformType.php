<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\CategorieOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class OffreformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('advantage')
            ->add('conditions')
            ->add('duration')
            ->add('discount')
            ->add('categorie', EntityType::class, [
                'class' => CategorieOffre::class,
                'choice_label' => 'categorie_name',
                'placeholder' => 'Choose a categorieOffre',
            ])
            // Add the image field
            ->add('offreimg', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false, // This means that this field is not mapped to any entity property
                'required' => false, // This field is not required
                'attr' => [
                    'accept' => 'image/jpeg, image/png', // Limit file types to JPEG and PNG
                ],
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
