<?php

namespace App\Form;

use App\Entity\Page;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageCreateType extends AbstractType
{
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('struct', ChoiceType::class, [
                'label' => $this->translator->trans('form.message'),
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('toTargets', CollectionType::class, [
                'entry_type' => TargetType::class,
                'entry_options' => ['fromPage' => $options['fromPage'], 'label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->add('content', TextareaType::class, [
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
            ->add('submit', SubmitType::class, [
                'label' => $this->translator->trans('button.submit'),
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'fromPage' => null,
        ]);
    }
}
