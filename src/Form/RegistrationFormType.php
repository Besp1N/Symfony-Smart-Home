<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email()
                ]
            ])
            ->add('name', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2])
                ]
            ])
            ->add('lastName', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 2])
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 6]),
                    new Assert\Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*\W)/',
                        'message' => 'Password must contain at least one uppercase letter and one special character.'
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
