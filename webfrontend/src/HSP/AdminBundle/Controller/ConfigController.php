<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 12/9/13
 * Time: 8:23 AM
 */

namespace HSP\AdminBundle\Controller;

use HSP\AdminBundle\Entity\IPV6Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConfigController extends Controller
{
  public function editConfigAction(Request $request)
  {
    $em = $this->getDoctrine()->getManager()->getRepository('HSPAdminBundle:IPV6Config');
    $config = $em->findOneBy(array('configInstanceName' => 'master'));
    if (empty($config)) {
      $config = new IPV6Config();
      $config->setConfigInstanceName('master');
    }
    $form = $this->createFormBuilder($config)
      ->add('securityToken', 'text', array('label' => 'Export security token', 'required' => false))
      ->add('maxExportItems', 'number', array('label' => 'Export max. items', 'required' => false))
      ->add('enableExports', 'checkbox', array('label' => 'Export enabled', 'required' => false, 'value' => 1))
      ->add('logPruningTime', 'number', array('label' => 'Log pruning time', 'required' => false))
      ->add('save', 'submit')->getForm();
    $form->handleRequest($request);
    if ($form->isValid()) {
      $this->get('session')->getFlashBag()->set('notice', 'Config has been stored');
      $em = $this->getDoctrine()->getManager();
      $em->persist($config);
      $em->flush();
    }
    return $this->render('HSPAdminBundle:Default:editconfig.html.twig', array(
      'name' => "config",
      'form' => $form->createView()
    ));
  }

  public function getConfig($configInstanceName = 'master')
  {
    $em = $this->getDoctrine()->getManager()->getRepository('HSPAdminBundle:IPV6Config');
    if (true == ($data = $em->findOneBy(array('configInstanceName' => 'master')))) {
      return $data;
    } else {
      throw $this->createNotFoundException('Config not found! Please edit first!');
    }
  }
}