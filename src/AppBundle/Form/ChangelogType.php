<?php

declare(strict_types=1);

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangelogType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Numero',
                'required' => true,
            ])->add('title', TextType::class, [
                'label' => 'Titulo',
                'required' => true,
            ])->add('content', TextareaType::class, [
                'attr' => [
                    'rows' => 20,
                    'class' => 'tinymce',
                ],
                'label' => '',
                'required' => false,
            ]);

        $builder->add('dateStart', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Fecha Inicio',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'datetimepicker',
            ],
        ]);

        $builder->add('dateEnd', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Fecha Fin',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'datetimepicker',
            ],
        ]);

        $builder->add('dateDeployed', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Fecha Despliegue',
            'format' => 'dd/MM/yyyy',
            'attr' => [
                'class' => 'datetimepicker',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Changelog',
            'translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_changelog_type';
    }
}
