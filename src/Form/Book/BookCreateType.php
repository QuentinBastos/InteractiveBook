<?php

namespace App\Form\Book;

use App\Entity\Book\Book;
use App\Form\Type\TypeType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BookCreateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'form.title',
                'row_attr' => [
                    'class' => 'w-full ',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 font-medium text-gray-900 text-lg',
                ],
                'attr' => [
                    'class' => 'py-2 block w-full rounded px-2 shadow focus:ring-gray-500 focus:border-gray-500',
                    'oninput' => 'updatePreview(event, "titlePreview")',
                ],
            ])
            ->add('filePath', FileType::class, [
                'label' => 'Image',
                'row_attr' => [
                    'class' => 'w-full mt-4',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 font-medium text-gray-900 text-lg',
                ],
                'required' => false,
                'data_class' => null,
                'attr' => [
                    'class' => 'w-full rounded shadow focus:ring-gray-500 focus:border-gray-500 bg-white py-1 px-2',
                    'oninput' => 'updateImagePreview(event, "imagePreview")',
                ],
            ])
            ->add('types', CollectionType::class, [
                'label' => ' ',
                'entry_type' => TypeType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'row_attr' => [
                    'class' => 'w-full mt-4',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 font-medium text-gray-900 text-lg',
                ],
                'attr' => [
                    'class' => 'pl-2 py-2 w-full rounded shadow py-1 px-2 flex flex-col hidden', // Hidden by default
                    'data-index' => "{{ form.types|length > 0 ? form.types|last.vars.name + 1 : 0 }}",
                    'data-prototype' => "{{ form_widget(form.types.vars.prototype)|e('html_attr') }}",
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Write',
                'row_attr' => [
                    'class' => 'w-full flex items-center justify-center mt-4',
                ],
                'attr' => [
                    'style' => 'background-color: black !important; color: white !important; width: 100%; ',
                    'class' => 'rounded py-2 my-4'
                ],
            ]);
    }



    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}