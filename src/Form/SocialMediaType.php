<?php

namespace App\Form;

use App\Entity\Agent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SocialMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('facebook', TextType::class, [
                'label' => 'Facebook',
                'attr'  => ['placeholder' => 'Url Facebook']
            ])
            ->add('twitter', TextType::class, [
                'label' => 'Twitter',
                'attr'  => ['placeholder' => 'Url Twitter']
            ])
            ->add('instagram', TextType::class, [
                'label' => 'Instagram',
                'attr'  => ['placeholder' => 'Url Instagram']
            ])
            ->add('pinterest', TextType::class, [
                'label' => 'Pinterest',
                'attr'  => ['placeholder' => 'Url Pinterest']
            ])
            ->add('dribbble', TextType::class, [
                'label' => 'Dribbble',
                'attr'  => ['placeholder' => 'Url Dribbble']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
