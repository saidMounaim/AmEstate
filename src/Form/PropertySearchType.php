<?php

namespace App\Form;

use App\Entity\Types;
use App\Entity\Property;
use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, [
                'label' => 'Keyword',
                'attr'  => ['placeholder'=>'Keyword', 'class' => 'form-control form-control-lg form-control-a']
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status',
                'attr'  => ['class'=>'form-control form-control-lg form-control-a'],
                'choices' => $this->getStatus(),
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type',
                'attr'  => ['class'=>'form-control form-control-lg form-control-a'],
                'class' => Types::class,
                'choice_label' => 'name',
            ])
            ->add('bedrooms', ChoiceType::class, [
                'label' => 'Bedrooms',
                'attr'  => ['class'=>'form-control form-control-lg form-control-a'],
                'choices' => [
                    'All' => null,
                    '01' => '1',
                    '02' => '2',
                    '03' => '3',
                    '04' => '4',
                ],
            ])
            ->add('bathrooms', ChoiceType::class, [
                'label' => 'Bathrooms',
                'attr'  => ['class'=>'form-control form-control-lg form-control-a'],
                'choices' => [
                    'All' => null,
                    '01' => '1',
                    '02' => '2',
                    '03' => '3',
                    '04' => '4',
                ],
            ])
            ->add('garage', ChoiceType::class, [
                'label' => 'Garage',
                'attr'  => ['class'=>'form-control form-control-lg form-control-a'],
                'choices' => [
                    'All' => null,
                    '01' => '1',
                    '02' => '2',
                    '03' => '3',
                    '04' => '4',
                ],
            ])
            ->add('minPrice', TextType::class, [
                'label' => 'Min Price',
                'required' => false,
                'attr'  => ['placeholder' => 'Min Price', 'class'=>'form-control form-control-lg form-control-a'],
            ])        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
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
