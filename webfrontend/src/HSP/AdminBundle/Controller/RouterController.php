<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/21/13
 * Time: 11:56 AM
 */

namespace HSP\AdminBundle\Controller;

use HSP\PageBundle\Entity\IPV6Router;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class RouterController extends Controller
{
  /**
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function routerHandlingAction()
  {
    $repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6Router');
    $routers = $repository->findAll();
    return $this->render('HSPAdminBundle:Default:routerlist.html.twig', array('routers' => $routers));
  }

  /**
   * create user form to add a router, and create new entry if postdata is given
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function routerAddEditAction(Request $request, $routerid = null)
  {
    if ($routerid !== null) {
      $em = $this->getDoctrine()->getManager()->getRepository('HSPPageBundle:IPV6Router');
      if (!$tmpRouter = $em->find($routerid)) {
        $this->get('session')->getFlashbag()->set('notice', 'Router not found!');
        return new RedirectResponse($this->generateUrl('hsp_admin_router_handling'));
      }
    }
    if ($routerid === null)
      $tmpRouter = new IPV6Router();
    $form = $this->createFormBuilder($tmpRouter)
      ->add('routername', 'text', array('label' => 'Routername', 'required' => true))
      ->add('fqdn', 'text', array('label' => 'FQDN/IP', 'required' => true))
      ->add('port', 'number', array('label' => 'Port', 'required' => false))
      ->add('userName', 'text', array('label' => 'SSH username', 'required' => false))
      ->add('password', 'text', array('label' => 'SSH password', 'required' => false))
      ->add('sshKey', 'textarea', array('label' => 'SSH Key', 'required' => false))
      ->add('active', 'checkbox', array('label' => 'Status (checked = active, unchecked = disabled)', 'required' => false, 'value' => 1))
      ->add('save', 'submit')
      ->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $this->get('session')->getFlashBag()->set('notice', 'saved routersettings ' . $tmpRouter->getRouterName());
      $em = $this->getDoctrine()->getManager();
      $em->persist($tmpRouter);
      $em->flush();
      return new RedirectResponse($this->generateUrl('hsp_admin_router_handling'));
    }
    return $this->render('HSPAdminBundle:Default:routeradd.html.twig', array('form' => $form->createView()));
  }

  /**
   * delete router from list confirmation
   * @param $routerid
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function routerDeleteAction($routerid)
  {
    $router = $this->getDoctrine()->getManager()->getRepository('HSPPageBundle:IPV6Router')->find($routerid);
    return $this->render('HSPAdminBundle:Default:routerdelete.html.twig', array('name' => $router->getRouterName(), 'id' => $routerid));
  }

  /**
   * delete router from list
   * @param $routerid
   * @return RedirectResponse
   */
  public function routerDeleteConfirmAction($routerid)
  {
    $em = $this->getDoctrine()->getManager();
    $routerName = $this->getDoctrine()->getManager()->getRepository('HSPPageBundle:IPV6Router')->find($routerid)->getRouterName();
    $router = $this->getDoctrine()->getManager()->getRepository('HSPPageBundle:IPV6Router')->find($routerid);
    $em->remove($router);
    $em->flush();
    $this->get('session')->getFlashBag()->set('notice', 'deleted router ' . $routerName);
    return new RedirectResponse($this->generateUrl('hsp_admin_router_handling'));
  }
}
