<?php

namespace App\Form;

use App\Entity\User;
use App\Validate\Constraints\ContainDigits;
use App\Validate\Constraints\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,
                [
                    'constraints' =>
                        [
                            new Email(['message' => $this->translator->trans("register.form_validation.email.empty")])
                        ]
                ])
            ->add('username')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans('register.form_validation.agreements'),
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => $this->translator->trans('register.form_validation.password.empty'),
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => $this->translator->trans('register.form_validation.password.password_length', ['%length%' => 6]),
                        'max' => 4096,
                    ]),
                    new ContainDigits(['message' => $this->translator->trans('register.form_validation.password.contain_digits')])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
