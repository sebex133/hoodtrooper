<?php

namespace App\Form;

use App\Entity\HoodtrooperPlace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class HoodtrooperPlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('private_area_place')
            ->add('discover_date', BirthdayType::class, [
                'format' => 'dd-MM-yyyy',
            ])
            ->add('description', TextareaType::class, [
//                'attr' => ['class' => 'tinymce'],
            ])
            ->add('recommendation_level', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices'  => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
            ])
//            ->add('private_area_place', ChoiceType::class, [
//                'expanded' => true,
//                'multiple' => true,
//                'choices'  => [
//                    'Private area place!' => true,
//                ],
//            ])
            ->add('image', FileType::class, [
                'label' => 'Place image (JPG/PNG)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a image file',
                    ])
                ],
            ])
            ->add('coordinate_lat')
            ->add('coordinate_lng')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HoodtrooperPlace::class,
        ]);
    }
}
