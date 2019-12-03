<?php

declare(strict_types=1);

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
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Sitio',
                'required' => true,
            ])->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
            ])->add('version', TextType::class, [
                'label' => 'Version',
                'required' => false,
            ])->add('facebookUrl', UrlType::class, [
                'label' => 'Url de Facebook',
                'required' => false,
            ])->add('enMantenimiento', CheckboxType::class, [
                'label' => 'Tienda en Mantenimiento',
                'required' => false,
                'attr' => [
                    'class' => 'minimal-red',
                ],
            ])->add('mostrarContacto', CheckboxType::class, [
                'label' => 'Mostrar Informacion de Contacto',
                'required' => false,
                'attr' => [
                    'class' => 'minimal-red',
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Form\Model\ConfigModel',
            'translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'config_type';
    }
}
