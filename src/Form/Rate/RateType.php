<?php

namespace App\Form\Rate;

use App\Entity\Book\Rate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', IntegerType::class, [
                'label' => 'Rate this book',
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                    'class' => 'form-control',
                    'placeholder' => 'Enter a rating between 1 and 5',
                ],
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Your comment',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Write your thoughts about the book...',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit Review',
                'attr' => [
                    'class' => 'btn btn-primary',
                    'style' => 'background-color: #1D4ED8; color: #FFFFFF;', // Couleur personnalisée
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rate::class,
        ]);
    }
}