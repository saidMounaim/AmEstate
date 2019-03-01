<?php

namespace App\Form;

use App\Entity\UpdatePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, [
                'label' => 'Old Password',
                'attr'  => ['placeholder' => 'Old Password']
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password',
                'attr'  => ['placeholder' => 'New Password']
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'attr'  => ['placeholder' => 'Confirm Password']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UpdatePassword::class
        ]);
    }
}
