<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class BookCreateType extends AbstractType
{


    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $book = new Book();
        $builder
            ->add('title', TextareaType::class, [
                'label' => $this->translator->trans('form.message'),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('filePath', FileType::class, [
                'label' => 'button.upload',
                'required' => false,
                'data_class' => null,
            ])
            ->add('type', ChoiceType::class, [
                'label' => $this->translator->trans('form.type'),
                'choices' => $book->getTypes(),
                'choice_label' => function ($choice) {
                    return $this->translator->trans('book.type.' . $choice);
                },
                'choice_value' => function ($choice) {
                    return $choice;
                },
            ])
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('button.submit'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
