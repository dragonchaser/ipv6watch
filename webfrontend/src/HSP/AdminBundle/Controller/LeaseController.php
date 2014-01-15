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

    var_dump($formData);
    $em = $this->getDoctrine()->getManager();
    $repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6TimeLog');
    if ($formData['filterBy'] == 'any' || $formData['filterBy'] == '') {
      echo "baz";
      $entries = $repository->findAll();
    } else {
      echo "foon";
      switch ($formData['filterBy']) {
        case 'ipv6':
          echo "v6";
          $query = $em->createQuery('SELECT tl FROM HSPPageBundle:IPV6Timelog tl
                                      JOIN HSPPageBundle:IPV6IpAddress ia
                                      WHERE
                                        ia.ipv6Address = :ipv6Addr');
          $query->setParameter('ipv6Addr', $formData['filter']);
          $entries = $query->getResult();
          break;
        case 'ipv4':
          echo "v4";
          $query = $em->createQuery('SELECT tl FROM HSPPageBundle:IPV6Timelog tl
                                      JOIN HSPPageBundle:IPV6IpAddress ia
                                      WHERE
                                        ia.ipv4Address = :ipv4Addr');
          $query->setParameter('ipv4Addr', $formData['filter']);
          $entries = $query->getResult();
          break;
        case 'mac':
          echo "mac";
          $query = $em->createQuery('SELECT tl FROM HSPPageBundle:IPV6Timelog tl
                                      JOIN HSPPageBundle:IPV6IpAddress ia
                                      WHERE
                                        ia.macAddress = :macAddr');
          $query->setParameter('macAddr', $formData['filter']);
          $entries = $query->getResult();
          break;
        default:
        case 'any';
        //$entries = $repository->findByipv6Address($formData['filter']);
        break;
      }
    }
    var_dump($entries);
    die();
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