<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Ui\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('identifier', null, [
                'label' => 'username',
                'required' => true,
                'mapped' => false,
                'attr' => ['style' => 'display:none;'],
                'label_attr' => ['style' => 'display:none;'],
            ])
            ->add('currentPassword', PasswordType::class, [
                'invalid_message' => 'error_passwords_different',
                'required' => true,
                'mapped' => false,
                'label' => 'current_password',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 8,
                        // max length allowed by Symfony for security reasons
                        'max' => 200,
                    ]),
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'error_passwords_different',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'mapped' => false,
                'first_options'  => [
                    'label' => 'new_password',
                    'attr' => ['autocomplete' => 'new-password'],
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 8,
                            // max length allowed by Symfony for security reasons
                            'max' => 200,
                        ]),
                    ],
                ],
                'second_options' => ['label' => 'reenter_password'],
            ]);
    }
}
