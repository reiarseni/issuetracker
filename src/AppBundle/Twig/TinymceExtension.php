<?php

declare(strict_types=1);

namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Twig Extension for TinyMCE support.
 */
class TinymceExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface Container interface
     */
    protected $container;

    /**
     * Initialize tinymce helper.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service
     */
    public function getService($id)
    {
        return $this->container->get($id);
    }

    /**
     * Get parameters from the service container.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'tinymce_init' => new \Twig_SimpleFunction(
                'tinymce_init',
                [$this, 'tinymceInit'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * TinyMce initializations.
     *
     * @param array $options
     *
     * @return string
     */
    public function tinymceInit($options = [])
    {
        return $this->getService('twig')->render('twigextension/tinymce_init.html.twig', [
        ]);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'tinymce';
    }
}
