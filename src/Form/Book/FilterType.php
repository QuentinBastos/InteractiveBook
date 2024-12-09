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
                    'placeholder' => 'Search by title, author...'
                ],
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'label' => 'book.form.author',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Choose author'
                ],
            ])
            ->add('rate', null, [
                'label' => 'book.form.rate',
                'required' => false,
                'attr' => [
                    'class' => 'form-control rate',
                ],
            ])
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'name',
                'label' => 'Types',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Select multiple options...'
                ],
            ])
            ->add('maxPage', null, [
                'label' => 'book.form.max_page',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Max pages'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'button.search',
                'attr' => [
                    'class' => 'bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 w-full'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
