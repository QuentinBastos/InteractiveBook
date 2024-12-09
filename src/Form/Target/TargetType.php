<?php

namespace App\Form\Target;

use App\Entity\Page;
use App\Entity\Target;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $book = $options['book'];
        $page = $options['page'];

        $builder
            ->add('toPage', EntityType::class, [
                'class' => Page::class,
                'choice_label' => 'title',
                'query_builder' => function (EntityRepository $er) use ($book, $page) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.toTargets', 't')
                        ->where('p.book = :book')
                        ->andWhere('t.fromPage = :page')
                        ->andWhere('p.id != :pageId')
                        ->setParameter('pageId', $page->getId())
                        ->setParameter('page', $page)
                        ->setParameter('book', $book);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Target::class,
            'book' => null,
            'page' => null,
        ]);
    }
}
