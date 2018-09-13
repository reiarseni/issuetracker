<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConfigType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array(
                'label' => 'Nombre del Sitio',
                'required' => true,
            ))->add('email', EmailType::class, array(
                'label' => 'Email',
                'required' => false,
            ))->add('version', TextType::class, array(
                'label' => 'Version',
                'required' => false,
            ))->add('facebookUrl', UrlType::class, array(
                'label' => 'Url de Facebook',
                'required' => false,
            ))->add('enMantenimiento', CheckboxType::class, array(
                'label' => 'Tienda en Mantenimiento',
                'required' => false,
                'attr' => array(
                    'class' => 'minimal-red',
                )
            ))->add('mostrarContacto', CheckboxType::class, array(
                'label' => 'Mostrar Informacion de Contacto',
                'required' => false,
                'attr' => array(
                    'class' => 'minimal-red',
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Form\Model\ConfigModel',
            'translation_domain' => 'messages',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'config_type';
    }
}
