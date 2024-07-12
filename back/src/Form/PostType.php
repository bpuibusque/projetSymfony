<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Commission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('commission', EntityType::class, [
                'class' => Commission::class,
                'choice_label' => 'name',
                'placeholder' => 'General',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Post']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
