<?php

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
            ->add('number', TextType::class, array(
                'label' => 'Numero',
                'required' => true,
            ))->add('title', TextType::class, array(
                'label' => 'Titulo',
                'required' => true,
            ))->add('content', TextareaType::class, array(
                'attr' => array(
                    'rows' => 20,
                    'class' => 'tinymce'
                ),
                'label' => '',
                'required' => false
            ));

        $builder->add('dateStart', DateTimeType::class, array(
            'widget' => 'single_text',
            'label' => 'Fecha Inicio',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'class' => 'datetimepicker',
            ),
        ));

        $builder->add('dateEnd', DateTimeType::class, array(
            'widget' => 'single_text',
            'label' => 'Fecha Fin',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'class' => 'datetimepicker',
            ),
        ));

        $builder->add('dateDeployed', DateTimeType::class, array(
            'widget' => 'single_text',
            'label' => 'Fecha Despliegue',
            'format' => 'dd/MM/yyyy',
            'attr' => array(
                'class' => 'datetimepicker',
            ),
        ));

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Changelog',
            'translation_domain' => 'messages',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_changelog_type';
    }
}
