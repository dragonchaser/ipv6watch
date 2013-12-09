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

    $menu->addChild('Config', array('route' => 'hsp_admin_edit_config'))
      // generate user submenu
      ->addChild('User', array('route' => 'hsp_admin_user_handling'))
      ->addChild('Add User', array('route' => 'hsp_admin_user_add'))

      // generate router config submenu
      ->getParent()->getParent()
      ->addChild('Router', array('route' => 'hsp_admin_router_handling'))
      ->addChild('Add Router', array('route' => 'hsp_admin_router_add'));
    // todo: add config submenu for exports basic settings etc

    $menu->addChild('Leases', array('route' => 'hsp_admin_leaselist'))
      ->addChild('Export', array('route' => 'hsp_admin_lease_exports'));
    return $menu;
  }
}