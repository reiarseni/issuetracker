<?php

declare(strict_types=1);

namespace AppBundle\Form;

use AppBundle\Entity\City;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
// 1. Include Required Namespaces
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
// Your Entity
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonType extends AbstractType
{
    private $em;

    /**
     * The Type requires the EntityManager as argument in the constructor. It is autowired
     * in Symfony 3.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // 2. Remove the dependent select from the original buildForm as this will be
        // dinamically added later and the trigger as well
        $builder->add('name')
            ->add('lastName');

        // 3. Add 2 event listeners for the form
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
        $builder->addEventListener(FormEvents::PRE_SUBMIT, [$this, 'onPreSubmit']);
    }

    public function onPreSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Search for selected City and convert it into an Entity
        $city = $this->em->getRepository('AppBundle:City')->find($data['city']);

        $this->addElements($form, $city);
    }

    public function onPreSetData(FormEvent $event)
    {
        $person = $event->getData();
        $form = $event->getForm();

        // When you create a new person, the City is always empty
        $city = $person->getCity() ? $person->getCity() : null;

        $this->addElements($form, $city);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Person',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_person';
    }

    protected function addElements(FormInterface $form, City $city = null)
    {
        // 4. Add the province element
        $form->add('city', EntityType::class, [
            'required' => true,
            'data' => $city,
            'placeholder' => 'Select a City...',
            'class' => 'AppBundle:City',
        ]);

        // Neighborhoods empty, unless there is a selected City (Edit View)
        $neighborhoods = [];

        // If there is a city stored in the Person entity, load the neighborhoods of it
        if ($city) {
            // Fetch Neighborhoods of the City if there's a selected city
            $repoNeighborhood = $this->em->getRepository('AppBundle:Neighborhood');

            $neighborhoods = $repoNeighborhood->createQueryBuilder('q')
                ->where('q.city = :cityid')
                ->setParameter('cityid', $city->getId())
                ->getQuery()
                ->getResult();
        }

        // Add the Neighborhoods field with the properly data
        $form->add('neighborhood', EntityType::class, [
            'required' => true,
            'placeholder' => 'Select a City first ...',
            'class' => 'AppBundle:Neighborhood',
            'choices' => $neighborhoods,
        ]);
    }
}
