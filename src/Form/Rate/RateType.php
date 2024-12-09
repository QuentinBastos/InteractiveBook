<?php

namespace App\Form\Rate;

use App\Entity\Book\Rate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rate', IntegerType::class, [
                'label' => 'rate.text',
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                ],
            ])
            ->add('comment', TextType::class, [
                'label' => 'rate.comment',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'rate.submit',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rate::class,
        ]);
    }
}