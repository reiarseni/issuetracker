<?php
namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('main');

        $menu->setChildrenAttributes(array(
            'class' => 'sidebar-menu tree',
            'data-widget' => 'tree'
        ));

        //Menu Inicio

        $inicio = $menu->addChild('dashboard', array(
            'route' => 'dashboard',
        ));

        $inicio->setExtras(array(
            'label' => 'Inicio',
            'icon' => 'fa fa-dashboard',
            'info' => '<small class="label pull-right bg-green">Inicio</small>',
            'safe_label' => true,
        ));

        $this->buildLabel($inicio);

        //Menu Proveedor

        $proveedor = $menu->addChild('Proveedor', array(
            'route' => 'proveedor_index',
        ));

        $proveedor->setExtras(array(
            'label' => 'Proveedor',
            'icon' => 'fa fa-barcode',
            'safe_label' => true,
        ));

        $this->buildLabel($proveedor);

        return $menu;
    }

    /**
     * Construye el texto HTML del label
     * @param ItemInterface $item
     */
    public function buildLabel(ItemInterface $item)
    {
        $options = $item->getExtras();

        $icon = isset($options['icon']) ? '<i class="' . $options['icon'] . '"></i>' : '';
        $label = isset($options['label']) ? '<span>' . $options['label'] . '</span>' : '';
        $info = isset($options['info']) ? '<span class="pull-right-container">' . $options['info'] . '</span>' : '';

        $item->setLabel($icon . $label . $info);
    }



}
