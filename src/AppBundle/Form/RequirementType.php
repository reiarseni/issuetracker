<?php

declare(strict_types=1);

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ])->add('reportedBy', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
                'label' => 'Reportado por',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('progress', NumberType::class, [
                'label' => 'Progreso',
                'required' => false,
                'attr' => [
                    'data-slider-value' => $options['data'] ? $options['data']->getProgress() : 0,
                ],
            ]);

        $builder->add('receivedAt', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'Fecha Recibido',
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
            'data_class' => 'AppBundle\Entity\Requirement',
            'translation_domain' => 'messages',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_issue_type';
    }
}
