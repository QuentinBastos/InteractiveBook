<?php

namespace App\Form\Page;

use App\Entity\Page;
use App\Form\Target\TargetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageCreateType extends AbstractType
{


    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $book = $options['book'];
        $page = new Page();

        $builder
            ->add('title', TextType::class, [
                'label' => 'form.title',
                'row_attr' => [
                    'class' => 'w-full',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 font-medium text-gray-900 text-lg',
                ],
                'attr' => [
                    'class' => 'py-2 block w-full rounded px-2 shadow focus:ring-gray-500 focus:border-gray-500',
                    'oninput' => 'updatePreview(event, "titlePreview")',
                ],
            ])
            ->add('struct', ChoiceType::class, [
                'label' => 'page.form.struct',
                'row_attr' => [
                    'class' => 'w-full mt-4',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 font-medium text-gray-900 text-lg',
                ],
                'choices' => $page->getStructChoices(),
                'choice_label' => function ($choice) {
                    return $this->translator->trans($choice);
                },
                'choice_value' => function ($choice) {
                    return $choice;
                },
                'attr' => [
                    'class' => 'pl-2 py-2 w-full rounded shadow py-1 px-2',
                ],
            ])
            ->add('toTargets', CollectionType::class, [
                'entry_type' => TargetType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'entry_options' => ['label' => false, 'book' => $book],
                'label' => ' ',
                'row_attr' => [
                    'class' => 'w-full mt-4',
                ],
            ])

            ->add('content', TextareaType::class, [
                'label' => 'page.form.message',
                'row_attr' => [
                    'class' => 'w-full mt-4',
                ],
                'label_attr' => [
                    'class' => 'block mb-2 font-medium text-gray-900 text-lg',
                ],
                'attr' => [
                    'class' => 'py-2 block w-full rounded px-2 shadow focus:ring-gray-500 focus:border-gray-500 resize-none',
                ],
            ])
            ->add('filePath', FileType::class, [
                'label' => 'button.upload',
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
            ->add('submit', SubmitType::class, [
                'label' => 'Write',
                'row_attr' => [
                    'class' => 'w-full flex items-center justify-center mt-4',
                ],
                'attr' => [
                    'style' => 'background-color: black !important; color: white !important; width: 100%',
                    'class' => 'rounded py-2',
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'book' => null,
        ]);
    }
}
