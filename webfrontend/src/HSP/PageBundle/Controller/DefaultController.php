<?php

namespace HSP\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
  public function indexAction()
  {
    $doctrine = $this->getDoctrine();
    $ipCount = count($doctrine->getRepository('HSPPageBundle:IPV6IpAddress')->findAll());
    $logCount = count($doctrine->getRepository('HSPPageBundle:IPV6TimeLog')->findAll());
    $routerCount = count($doctrine->getRepository('HSPPageBundle:IPV6Router')->findAll());
    $runCount = count($doctrine->getRepository('HSPPageBundle:IPV6CronRuns')->findAll());
    return $this->render('HSPPageBundle:Default:index.html.twig',
      array(
        'ipCount' => $ipCount,
        'logCount' => $logCount,
        'routerCount' => $routerCount,
        'runCount' => $runCount
      )
    );
  }
}
