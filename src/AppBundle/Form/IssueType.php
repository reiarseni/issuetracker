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

class IssueType extends AbstractType
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
            ])->add('estimatedHours', NumberType::class, [
                'label' => 'Horas Estimadas',
                'required' => true,
            ])
            ->add('actualHours', NumberType::class, [
                'label' => 'Horas Reales',
                'required' => true,
            ])->add('content', TextareaType::class, [
                'attr' => [
                    'rows' => 20,
                    'class' => 'tinymce',
                ],
                'label' => '',
                'required' => false,
            ])
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'label' => 'Categoria',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('status', EntityType::class, [
                'class' => 'AppBundle\Entity\IssueStatus',
                'label' => 'Estado',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('priority', EntityType::class, [
                'class' => 'AppBundle\Entity\IssuePriority',
                'label' => 'Prioridad',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('type', EntityType::class, [
                'class' => 'AppBundle\Entity\IssueType',
                'label' => 'Tipo',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('reportedBy', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
                'label' => 'Reportado por',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('requirement', EntityType::class, [
                'class' => 'AppBundle\Entity\Requirement',
                'label' => 'Requirement',
                'required' => false,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('assignedTo', EntityType::class, [
                'class' => 'AppBundle\Entity\User',
                'label' => 'Asignado a',
                'required' => true,
                'placeholder' => '---Seleccione---',
                'attr' => [
                    'class' => 'select2personal',
                ],
            ])->add('tags', EntityType::class, [
                'label' => 'Tags',
                'required' => false,
                'multiple' => true,
                'class' => 'AppBundle\Entity\IssueTag',
                'attr' => [
                    'size' => '4',
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
        $builder->add('deadlineAt', DateTimeType::class, [
            'widget' => 'single_text',
            'label' => 'issue.form.deadlineAt',
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
            'data_class' => 'AppBundle\Entity\Issue',
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
