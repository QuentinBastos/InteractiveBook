<?php

namespace App\Form\Book;

use App\Entity\Book\Book;
use App\Entity\Book\Type;
use App\Form\Type\TypeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'form.title',
            ])
            ->add('filePath', FileType::class, [
                'label' => 'button.upload',
                'required' => false,
                'data_class' => null,
            ])
            ->add('types', CollectionType::class, [
                'entry_type' => TypeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => ' ',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'button.submit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}