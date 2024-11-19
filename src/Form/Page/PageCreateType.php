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
            ])
            ->add('struct', ChoiceType::class, [
                'label' => 'page.form.struct',
                'choices' => $page->getStructChoices(),
                'choice_label' => function ($choice) {
                    return $this->translator->trans($choice);
                },
                'choice_value' => function ($choice) {
                    return $choice;
                },
            ])
            ->add('toTargets', CollectionType::class, [
                'entry_type' => TargetType::class,
                'allow_add' => true,
                'entry_options' => ['book' => $book],
                'allow_delete' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'page.form.message',
            ])
            ->add('filePath', FileType::class, [
                'label' => 'button.upload',
                'required' => false,
                'data_class' => null,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'button.submit',
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
