<?php

namespace App\Form;

use App\Entity\CategorieOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

class CategorieOffreformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie_name', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[A-Z][a-zA-Z]{1,9}$/',
                        'message' => 'Le nom de la catégorie doit commencer par une majuscule et contenir entre 2 et 10 lettres.'
                    ])
                ]
            ])
            ->add('description_cat', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[A-Z].{0,29}\.$/',
                        'message' => 'La description doit commencer par une majuscule, contenir maximum 30 mots et se terminer par un point.'
                    ])
                ]
            ])
            ->add('catimg', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false, // This means that this field is not mapped to any entity property
                'required' => false, // This field is not required
                'attr' => [
                    'accept' => 'image/jpeg, image/png', // Limit file types to JPEG and PNG
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CategorieOffre::class,
        ]);
    }
}
