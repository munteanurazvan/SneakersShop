<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add ('name', null,
            [
                'required' => false,
            ])
            ->add ('price_range', ChoiceType::class,
                ['choices' => [
                    '0-99' => '0-99',
                    '100-299'=> '100-299',
                    '300-999'=> '300-999',
                    ],
                    'multiple' => true,
                    'required' => false,
                    'expanded' => true
                ])

            ->add ('categories', EntityType::class,
                [
                    'class' => Category::class,
                    'multiple' => true,
                    'required' => false,
                    'expanded' => true
                ])
            ->add ('Filter', SubmitType::class);
    }
}
