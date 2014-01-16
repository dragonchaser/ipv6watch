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
      ->add('filter', 'text', array('label' => 'search for', 'required' => true))
      ->add('filterBy', 'choice', array(
        'choices' => array(
          'ipv4' => 'IPV4 Address',
          'ipv6' => 'IPV6 Address',
          'mac' => 'MAC Address'
        ),
        'label' => 'in'
      ))
      ->add('submit', 'submit')
      ->getForm();
    $form->handleRequest($request);
    $formData = $form->getData();
    if (!isset($formData['filterBy'])) $formData['filterBy'] = 'any';
    if (!isset($formData['filter'])) $formData['filter'] = ''; else $formData['filter'] = trim($formData['filter']);

    $em = $this->getDoctrine()->getManager();
    $repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6TimeLog');
    $formData['filter'] = preg_replace('/\*/', '%', $formData['filter']);
    if ($formData['filterBy'] == 'any' || $formData['filterBy'] == '') {
      $entries = $repository->findAll();
    } else {
      switch ($formData['filterBy']) {
        case 'ipv6':
          $query = $em->createQuery('SELECT tl FROM HSPPageBundle:IPV6Timelog tl
                                      JOIN HSPPageBundle:IPV6IpAddress ia
                                      WHERE
                                        ia.ipv6Address LIKE :ipv6Addr AND ia.id = tl.ipId');
          $query->setParameter('ipv6Addr', $formData['filter']);
          break;
        case 'ipv4':
          $query = $em->createQuery('SELECT tl FROM HSPPageBundle:IPV6Timelog tl
                                      JOIN HSPPageBundle:IPV6IpAddress ia
                                      WHERE
                                        ia.ipv4Address LIKE :ipv4Addr AND ia.id = tl.ipId');
          $query->setParameter('ipv4Addr', $formData['filter']);
          break;
        case 'mac':
          $query = $em->createQuery('SELECT tl FROM HSPPageBundle:IPV6Timelog tl
                                      JOIN HSPPageBundle:IPV6IpAddress ia
                                      WHERE
                                        ia.macAddress LIKE :macAddr AND ia.id = tl.ipId');
          $query->setParameter('macAddr', $formData['filter']);
          break;
        default:
      };
      //$entries = $query->getResult();
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
    $entries = $this->getDoctrine()->getManager()->createQuery('SELECT tl FROM HSPPageBundle:IPV6TimeLog tl JOIN HSPPageBundle:IPV6Router rt WHERE tl.hasBeenExported = 0')->getResult();
    $i = 0;
    foreach ($entries as $lease) {
      if ($i == $this->get('hsp_admin.config')->getConfig()->getMaxExportItems()) break;
      $em = $this->getDoctrine()->getManager();
      $query = $em->createQuery('UPDATE HSPPageBundle:IPV6TimeLog tl SET tl.hasBeenExported = 1 WHERE tl.id = :id');
      $query->setParameter('id', $lease->getId());
      $query->execute();
      $leases[] = $lease;
      $i++;
    }
    return $this->render('HSPAdminBundle:Default:export.csv.twig',
      array(
        'leases' => $leases)
    );
  }
} 