<?php
namespace HSP\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root'); 
        $menu->addChild('Home', array('route' => 'hsp_page_homepage'));

        $menu->addChild('Admin', array('route' => 'hsp_admin_link'))
             ->addChild('Logout', array('route' => 'fos_user_security_logout'));
        
        $menu->addChild('Users', array('route' => 'hsp_admin_user_handling'))
        	 #->addChild('Add User', array('route' => 'fos_user_security_logout'));
        	 ->addChild('Add User', array('route' => 'fos_user_registration_register'));
         
        /*$menu->addChild('About Me', array(
            'route' => 'page_show',
            'routeParameters' => array('id' => 42)
        ));*/
        // ... add more children

        return $menu;
    }
}