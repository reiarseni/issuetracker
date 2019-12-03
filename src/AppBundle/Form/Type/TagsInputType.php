<?php

declare(strict_types=1);

namespace AppBundle\Form\Type;

use AppBundle\Entity\IssueTag;
use AppBundle\Form\DataTransformer\IssueTagArrayToStringTransformer;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class TagsInputType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $manager;

    public function setContainer(EntityManager $manager = null)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // The Tag collection must be transformed into a comma separated string.
            // We could create a custom transformer to do Collection <-> string in one step,
            // but here we're doing the transformation in two steps (Collection <-> array <-> string)
            // and reuse the existing CollectionToArrayTransformer.
            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            ->addModelTransformer(new IssueTagArrayToStringTransformer($this->manager), true);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['tags'] = $this->manager->getRepository(IssueTag::class)->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return TextType::class;
    }
}
