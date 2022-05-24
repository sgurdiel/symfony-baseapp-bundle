<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Ui\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RequestRecoverPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'email',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Email([], 'error_invalid_email')
                ]
            ]);
    }
}
