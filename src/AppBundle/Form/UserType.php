<?php

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
            ->add('email', EmailType::class, array(
                'translation_domain' => 'FOSUserBundle',
                'attr' => array('class' => 'form-control')
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('attr' => array('class' => 'form-control')),
                'second_options' => array('attr' => array('class' => 'form-control')),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            //Esto se debe automanejar internamente
            ->add('roles', ChoiceType::class, array(
                'choices' => array(
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN' => 'ROLE_ADMIN'
                ),
                'multiple' => true,
                'label' => 'Rol',
                'attr' => array(
                    'style' => 'width:100%',
                ),
            ))
            ->add('enabled', CheckboxType::class, array(
                'label' => 'global.active',
                'required' => false,
                'attr' => array(
                    'class' => 'minimal-red',
                ),
            ));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'onPreSetDataUsername'));

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

            $data = $event->getData();
            $form = $event->getForm();

            $options = array(
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array(
                    'label' => 'form.password',
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation',
                    'attr' => array(
                        'class' => 'form-control',
                    )
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            );

            if ($data->getId() !== null) {
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
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'translation_domain' => 'messages',
        ));
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
            $form->add('username', null, array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.username',
                'attr' => array('class' => 'form-control')
            ));
        } else {
            $form->add('username', null, array(
                'translation_domain' => 'FOSUserBundle',
                'label' => 'form.username',
                'attr' => array(
                    'readonly' => true,
                )
            ));
        }
    }
}
