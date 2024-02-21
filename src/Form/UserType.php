<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use DateTime;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder
        ->add('first_name')
        ->add('last_name')
        ->add('email')
        ->add('password', PasswordType::class)
        ->add('phone_number')
        ->add('birth_date', DateType::class, [
            'widget' => 'single_text',
            'required' => true,
            'html5' => true,
        ])
        ->add('roles', ChoiceType::class, [
            'choices' => [
                'Admin' => 'ROLE_ADMIN',
                'Client' => 'ROLE_CLIENT',
                'Agent' => 'ROLE_AGENT',
            ],
            'multiple' => true, // Optional, if you want checkboxes/radio buttons for better UI
            'expanded' => true,
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
