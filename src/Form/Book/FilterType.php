<?php

namespace App\Form\Book;

use App\Entity\Book\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{


    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('search', TextType::class, [
                'label' => 'book.form.search',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'name',
                'label' => 'book.form.author',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'form.choose',
            ])
            ->add('rate', NumberType::class, [
                'label' => 'book.form.rate',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'label' => 'book.form.types',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
                'placeholder' => 'Select multiple options...',
            ])
            ->add('maxPage', NumberType::class, [
                'label' => 'book.form.max_page',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'button.search',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
