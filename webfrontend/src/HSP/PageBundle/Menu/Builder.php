<?php
namespace HSP\PageBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
 		
        $menu->addChild('Home', array('route' => 'hsp_page_homepage'));
        $menu->addChild('Admin', array('route' => 'hsp_admin_link'));
                
        /*$menu->addChild('About Me', array(
            'route' => 'page_show',
            'routeParameters' => array('id' => 42)
        ));*/
        // ... add more children

        return $menu;
    }
}