<?php

declare(strict_types=1);

namespace App\Auth\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class PasswordResetForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'password',
            PasswordType::class,
            [
                'label' => 'New Password',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter your new password',
                ],
            ]
        );
        $builder->add(
            'confirm_password',
            PasswordType::class,
            [
                'label' => 'Confirm Password',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Confirm your new password',
                ],
            ]
        );
    }
}
