<?php
/* TODO: add acl handling */
namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
  /**
   * Default index page of the admin controller
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function indexAction()
  {
    return $this->render('HSPAdminBundle:Default:index.html.twig', array(
      'name' => "admin"
    ));
  }
}
