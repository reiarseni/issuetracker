<?php

declare(strict_types=1);

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'translation_domain' => 'FOSUserBundle',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'options' => ['translation_domain' => 'FOSUserBundle'],
                'first_options' => ['attr' => ['class' => 'form-control']],
                'second_options' => ['attr' => ['class' => 'form-control']],
                'invalid_message' => 'fos_user.password.mismatch',
            ])
            //Esto se debe automanejar internamente
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ],
                'multiple' => true,
                'label' => 'Rol',
                'attr' => [
                    'style' => 'width:100%',
                ],
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'global.active',
                'required' => false,
                'attr' => [
                    'class' => 'minimal-red',
                ],
            ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetDataUsername']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $options = [
                'type' => PasswordType::class,
                'options' => ['translation_domain' => 'FOSUserBundle'],
                'first_options' => [
                    'label' => 'form.password',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'second_options' => [
                    'label' => 'form.password_confirmation',
                    'attr' => [
                        'class' => 'form-control',
                    ],
                ],
                'invalid_message' => 'fos_user.password.mismatch',
            ];

            if (null !== $data->getId()) {
                $options['required'] = false;
            }

            $form->add('plainPassword', RepeatedType::class, $options);
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user_type';
    }

    /**
     * @param FormEvent $event
     */
    public function onPreSetDataUsername(FormEvent $event)
    {
        $user = $event->getData();
        $form = $event->getForm();

        if (!$user || null === $user->getId()) {
            $form->add('username', null, [
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.username',
                'attr' => ['class' => 'form-control'],
            ]);
        } else {
            $form->add('username', null, [
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.username',
                'attr' => [
                    'readonly' => true,
                ],
            ]);
        }
    }
}
