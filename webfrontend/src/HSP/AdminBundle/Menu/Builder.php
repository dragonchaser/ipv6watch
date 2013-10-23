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
        $menu->addChild('Admin', array('route' => 'hsp_admin_link'));
        
        
        $menu->addChild('User', array('route' => 'hsp_admin_user_handling'))
        	 ->addChild('Add User', array('route' => 'fos_user_registration_register'))
        	 ->addChild('Change Password', array('route' => 'fos_user_change_password'))
             ->addChild('Logout', array('route' => 'fos_user_security_logout'));
        return $menu;
    }
}