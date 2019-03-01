<?php

namespace App\Form;

use App\Entity\Types;
use App\Form\ImageType;
use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'attr'  => ['class'=>'form-control form-control-lg form-control-a', 'placeholder'=>'Title']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => 'Description']
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => false,
                'required' => false,
                'allow_delete' => false,
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]
            ])
            ->add('adresse', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => 'Adresse']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'attr' => ['class' => 'form-control form-control-lg form-control-a'],
                'choices' => $this->getStatus(),
            ])
            ->add('area', TextType::class, [
                'label' => 'Area',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => '800']
            ])
            ->add('bedrooms', TextType::class, [
                'label' => 'Bedrooms',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => '3']
            ])
            ->add('bathrooms', TextType::class, [
                'label' => 'Bathrooms',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => '3']
            ])
            ->add('garage', TextType::class, [
                'label' => 'Garage',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => '3']
            ])
            ->add('city', TextType::class, [
                'label' => 'City',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => 'London']
            ])
            ->add('price', TextType::class, [
                'label' => 'Price',
                'attr' => ['class' => 'form-control form-control-lg form-control-a', 'placeholder' => '200']
            ])
            ->add('types', EntityType::class, [
                'attr' => ['class' => 'form-control form-control-lg form-control-a'],
                'class' => Types::class,
                'choice_label' => 'name',
            ])
            ->add('images', CollectionType::class, [
                'label' => false,
                'entry_type' => ImageType::class,
                'allow_add'  => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }


    private function getStatus()
    {
        $choices = Property::STATUS;
        $output = [];
        foreach ($choices as $k => $v) {
            $output[$v] = $k;
        }
        return $output;
    }

}
