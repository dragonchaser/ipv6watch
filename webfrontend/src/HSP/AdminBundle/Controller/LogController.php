<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 12/10/13
 * Time: 6:36 PM
 */

namespace HSP\AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LogController extends Controller
{
  public function logViewerAction()
  {
    $repository = $this->getDoctrine()->getRepository('HSPPageBundle:IPV6CronLog');
    $logentries = $repository->findBy(array(), array('id' => 'DESC'), 50);
    return $this->render('HSPAdminBundle:Default:loglist.html.twig', array(
      'logentries' => $logentries
    ));
  }
} 