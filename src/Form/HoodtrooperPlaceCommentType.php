<?php

namespace App\Form;

use App\Entity\HoodtrooperPlaceComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HoodtrooperPlaceCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment_text')
//            ->add('comment_author')
//            ->add('comment_related_place')
            ->add('author_id_hidden',TextType::class, [
                'label_attr' => ['hidden' => 'hidden'],
                'attr' => ['hidden' => 'hidden', 'disabled' => 'disabled'],
                'mapped' => false,
                'required' => true,
            ])
            ->add('place_id_hidden',TextType::class, [
                'label_attr' => ['hidden' => 'hidden'],
                'attr' => ['hidden' => 'hidden', 'disabled' => 'disabled'],
                'mapped' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HoodtrooperPlaceComment::class,
        ]);
    }
}
