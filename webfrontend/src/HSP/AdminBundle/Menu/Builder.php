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


    if ($securityContext = $this->container->get('security.context')->getToken()->getUser() != 'anon.') {
      $menu->addChild('Leases', array('route' => 'hsp_admin_leaselist'))
        ->addChild('Export', array('route' => 'hsp_admin_lease_exports'));

      $menu->addChild('Log', array('route' => 'hsp_admin_log'));
      $menu->addChild('Config', array('route' => 'hsp_admin_edit_config'))
        // generate user submenu
        ->addChild('User', array('route' => 'hsp_admin_user_handling'))
        ->addChild('Add User', array('route' => 'hsp_admin_user_add'))

        // generate router config submenu
        ->getParent()->getParent()
        ->addChild('Router', array('route' => 'hsp_admin_router_handling'))
        ->addChild('Add Router', array('route' => 'hsp_admin_router_add'));

    }
    return $menu;
  }
}