<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/21/13
 * Time: 12:00 PM
 */

namespace HSP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGenerator;

// TODO: implement filters & paginate(?)

class LeaseController extends Controller
{

  /**
   * generate list of ipv6 leases
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function leaseListAction(Request $request)
  {
    $form = $this->createFormBuilder()
      ->add('filter', 'text', array('label' => 'search for', 'required' => false))
      ->add('filterBy', 'choice', array(
        'choices' => array(
          'any' => 'any',
          'ipv6' => 'IPV6 Address',
          'ipv4' => 'IPV4 Address',
          'max' => 'MAC Address'
        ),
        'label' => 'in'
      ))
      ->add('submit', 'submit')
      ->getForm();
    $form->handleRequest($request);
    $formData = $form->getData();
    if (!isset($formData['filterBy'])) $formData['filterBy'] = 'any';
    if (!isset($formData['filter'])) $formData['filter'] = ''; else $formData['filter'] = trim($formData['filter']);
    $this->get('session')->getFlashBag()->set('notice', $formData['filterBy']);
    $repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6IpAddress');
    if (empty($formData['filter']))
      $entries = $repository->findAll();
    else
      switch ($formData['filter']) {
        case 'ipv6':
          $entries = $repository->findByipv6Address($formData['filter']);
          break;
        case 'ipv4':
          $entries = $repository->findByipv4Address($formData['filter']);
          break;
        case 'mac':
          $entries = $repository->findBymacAddress($formData['filter']);
          break;
        default:
        case 'any';
          $entries = $repository->findByipv6Address($formData['filter']);
          break;
      }
    return $this->render('HSPAdminBundle:Default:leaselist.html.twig',
      array('leases' => $entries, 'form' => $form->createView()));
  }

  /**
   * link to export
   * @return \Symfony\Component\HttpFoundation\Response
   */
  public function leaseExportsAction()
  {
    $configManager = $this->get('hsp_admin.config')->getConfig();
    return $this->render('HSPAdminBundle:Default:export.html.twig',
      array('enabled' => ($configManager->getEnableExports() == false ? false : true),
        'csvexportlink' => $this->generateUrl('hsp_admin_lease_export_csv',
            array(
              'securityToken' => $configManager->getSecurityToken()), UrlGenerator::ABSOLUTE_URL)
      )
    );
  }

  /**
   * render the number of exportitems as csv file
   * @param $securityToken
   * @return RedirectResponse|\Symfony\Component\HttpFoundation\Response
   */
  public function leaseExportsCSVAction($securityToken)
  {
    $configManager = $this->get('hsp_admin.config')->getConfig();
    if ($configManager->getEnableExports() == false ||
      $configManager->getSecurityToken() !== $securityToken
    ) {
      return new RedirectResponse($this->generateUrl('hsp_admin_link'));
    }
    return $this->render('HSPAdminBundle:Default:export.csv.twig', array('name' => 'CSV'));
  }
} 