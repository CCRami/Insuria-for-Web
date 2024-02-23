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
                        'message' => 'The cantegory name must start with an uppercase letter and contain between 2 and 10 letters.'
                    ])
                ]
            ])
            ->add('description_cat', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[A-Z].{0,29}\.$/',
                        'message' => 'The description_cat must start with an uppercase letter , contain maximum 30 words and ends with ".".'
                        
                    ])
                ]
            ])
            ->add('catimg', FileType::class, [
                'label' => 'Image (JPEG, PNG)',
                'mapped' => false,
                'required' => false, 
                'attr' => [
                    'accept' => 'image/jpeg, image/png', 
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
