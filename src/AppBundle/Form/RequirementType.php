<?php

namespace AppBundle\Form;

use AppBundle\Form\Type\TagsInputType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequirementType extends AbstractType
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
            ))->add('reportedBy', EntityType::class, array(
                'class' => 'AppBundle\Entity\User',
                'label' => 'Reportado por',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => array(
                    'class' => 'select2personal',
                )
            ))->add('progress', NumberType::class, array(
                'label' => 'Progreso',
                'required' => false,
                'attr' => array(
                    'data-slider-value' => $options['data'] ? $options['data']->getProgress() : 0
                ),
            ));

        $builder->add('receivedAt', DateTimeType::class, array(
            'widget' => 'single_text',
            'label' => 'Fecha Recibido',
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
            'data_class' => 'AppBundle\Entity\Requirement',
            'translation_domain' => 'messages',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_issue_type';
    }
}
