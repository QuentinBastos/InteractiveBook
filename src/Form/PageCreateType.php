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

class PageCreateType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $book = $options['book'];

        $builder
            ->add('title', null, [
                'label' => 'form.title',
            ])
            ->add('struct', ChoiceType::class, [
                'label' => 'form.message',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('toTargets', CollectionType::class, [
                'entry_type' => TargetType::class,
                'allow_add' => true,
                'entry_options' => ['book' => $book],
                'allow_delete' => true,
            ])
            ->add('content', TextareaType::class, [
                'label' => 'form.message',
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
                'label' => 'button.submit',
                'attr' => [
                    'class' => 'btn btn-primary',
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
