<?php

namespace App\Form;

use App\Entity\Offre;
use App\Entity\CategorieOffre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\File;

class OffreformType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('advantage', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4]),
                    new \Symfony\Component\Validator\Constraints\Regex([
                        'pattern' => '/^[A-Z].*/',
                        'message' => 'The advantage must start with an uppercase letter.'
                    ])
                ]
            ])
            ->add('conditions', null, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4]),
                    new \Symfony\Component\Validator\Constraints\Regex([
                        'pattern' => '/^[A-Z].*/',
                        'message' => 'The condition must start with an uppercase letter.'
                    ])
                ]
            ])
            ->add('duration', null, [
                'constraints' => [
                    new GreaterThan(['value' => 0])
                ]
            ])
            ->add('discount', null, [
                'constraints' => [
                    new GreaterThan(['value' => 0]),
                    new Range(['min' => 0, 'max' => 100])
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => CategorieOffre::class,
                'choice_label' => 'categorie_name',
                'placeholder' => 'Choose a categorieOffre',
            ])
            ->add('offreimg', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false, // This means that this field is not mapped to any entity property
                'required' => false, // This field is not required
                'constraints' => [
                    new File([
                        'maxSize' => '5024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image',
                    ])
                ],
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
