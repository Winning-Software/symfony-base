<?php

declare(strict_types=1);

namespace App\Auth\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'email',
            EmailType::class,
            [
                'label' => 'Email Address',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter your email address',
                ],
            ]
        );
        $builder->add(
            'password',
            PasswordType::class,
            [
                'label' => 'Password',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Enter your password',
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
                    'placeholder' => 'Confirm your password',
                ],
            ]
        );
    }
}
