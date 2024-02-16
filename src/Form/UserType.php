<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('first_name')
        ->add('last_name')
        ->add('email')
        ->add('password')
        ->add('phone_number')
        ->add('birth_date', DateType::class, [
            'widget' => 'single_text',
            'html5' => true,
            'constraints' => [
                new LessThanOrEqual([
                    'value' => 'today',
                    'message' => 'Birth date must not be in the future.',
                ]),
            ],
        ])
        ->add('role', ChoiceType::class, [
            'choices' => [
                'Admin' => 'admin',
                'Client' => 'client',
                'Agent' => 'agent',
            ],
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
