<?php
declare(strict_types=1);
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class QuestionnaireFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('age', ChoiceType::class, [
                'label' => 'What is your age?',
                'choices' => [
                    'Under 30' => 'under_30',
                    '30-50' => '30_50',
                    'Over 50' => 'over_50',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('income', ChoiceType::class, [
                'label' => 'What is your annual income?',
                'choices' => [
                    'Less than 15000 DT' => 'lt_15000',
                    '15000 DT - 30000 DT' => '15000_30000',
                    'Over 30000 DT' => 'gt_30000',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('marital_status', ChoiceType::class, [
                'label' => 'What is your marital status?',
                'choices' => [
                    'Single' => 'single',
                    'Married' => 'married',
                    'Divorced' => 'divorced',
                    'Widowed' => 'widowed',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('employment_status', ChoiceType::class, [
                'label' => 'What is your employment status?',
                'choices' => [
                    'Employed full-time' => 'employed_full_time',
                    'Employed part-time' => 'employed_part_time',
                    'Self-employed' => 'self_employed',
                    'Unemployed' => 'unemployed',
                    'Retired' => 'retired',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('health_status', ChoiceType::class, [
                'label' => 'What is your healthStatus?',
                'choices' => [
                    'Good' => 'good',
                    'Fair' => 'fair',
                    'Poor' => 'poor',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('risk_tolerance', ChoiceType::class, [
                'label' => 'What is your riskTolerance?',
                'choices' => [
                    'Low Risk Tolerance' => 'low_risk_tolerance',
                    'High Risk Tolerance' => 'high_risk_tolerance',
                    
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('assets', NumberType::class, [
                'label' => 'Total value of your assets (in currency):',
                'required' => true,
            ])
            ->add('liabilities', NumberType::class, [
                'label' => 'Total value of your liabilities (in currency):',
                'required' => true,
            ])
            ->add('financial_goals', ChoiceType::class, [
                'label' => 'What are your financial goals?',
                'choices' => [
                    'Retirement' => 'retirement',
                    'Education' => 'education',
                    'Saving for a major purchase' => 'major_purchase',
                    // Add more options as needed
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('preferred_coverage', ChoiceType::class, [
                'label' => 'What is your preferred coverage level?',
                'choices' => [
                    'High Coverage' => 'high_coverage',
                    'Medium Coverage' => 'medium_coverage',
                    'Basic Coverage' => 'basic_coverage',
                    // Add more options as needed
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('geographic_factors', ChoiceType::class, [
                'label' => 'What is your geographic location?',
                'choices' => [
                    'Urban' => 'urban',
                    'Rural' => 'rural',
                    // Add more options as needed
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
