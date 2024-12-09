<?php

namespace App\Form\Rate;

use App\Entity\Book\Rate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', null, [
                'label' => 'Rate this book',
                'attr' => [
                    'class' => 'star-rating',
                    'min' => 1,
                    'max' => 5,
                ],
                'required' => true,
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