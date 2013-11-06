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
			->addChild('Add User', array('route' => 'fos_user_registration_register'))->getParent()
			->addChild('Change Password', array('route' => 'fos_user_change_password'))->getParent()
			->addChild('Logout', array('route' => 'fos_user_security_logout'));

		$menu->addChild('Leases', array('route' => 'hsp_admin_leaselist'));

		$menu->addChild('Router', array('route' => 'hsp_admin_router_handling'))
			->addChild('Add Router', array('route' => 'hsp_admin_router_add'));
		return $menu;
	}
}