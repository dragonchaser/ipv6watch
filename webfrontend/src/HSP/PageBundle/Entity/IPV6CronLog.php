<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/6/13
 * Time: 2:38 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_cronlog");
 */
class IPV6CronLog
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="IPV6CronRuns")
   * @ORM\JoinColumn(name="IPV6CronRuns", referencedColumnName="id")
   */
  private $cronid;

  /**
   * @ORM\Column(type="datetime")
   */
  private $time;
  /**
   * @ORM\ManyToOne(targetEntity="IPV6Router")
   * @ORM\JoinColumn(name="router_id", referencedColumnName="id")
   */
  private $RouterId;
  /**
   * @ORM\Column(type="integer");
   */
  private $type;
  /**
   * @ORM\Column(type="string");
   */
  private $logentry;

  /**
   * @ORM\Column(type="datetime")
   */
  private $endtime;

  /**
   * Get id
   *
   * @return integer
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set cronid
   *
   * @param integer $cronid
   * @return IPV6CronLog
   */
  public function setCronid($cronid)
  {
    $this->cronid = $cronid;

    return $this;
  }

  /**
   * Get cronid
   *
   * @return integer
   */
  public function getCronid()
  {
    return $this->cronid;
  }

  /**
   * Set time
   *
   * @param \DateTime $time
   * @return IPV6CronLog
   */
  public function setTime($time)
  {
    $this->time = $time;

    return $this;
  }

  /**
   * Get time
   *
   * @return \DateTime
   */
  public function getTime()
  {
    return $this->time;
  }

  /**
   * Set type
   *
   * @param integer $type
   * @return IPV6CronLog
   */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }

  /**
   * Get type
   *
   * @return integer
   */
  public function getType()
  {
    return $this->type;
  }

  /**
   * Set logentry
   *
   * @param string $logentry
   * @return IPV6CronLog
   */
  public function setLogentry($logentry)
  {
    $this->logentry = $logentry;

    return $this;
  }

  /**
   * Get logentry
   *
   * @return string
   */
  public function getLogentry()
  {
    return $this->logentry;
  }

  /**
   * Set RouterId
   *
   * @param \HSP\PageBundle\Entity\IPV6Router $routerId
   * @return IPV6CronLog
   */
  public function setRouterId(\HSP\PageBundle\Entity\IPV6Router $routerId = null)
  {
    $this->RouterId = $routerId;

    return $this;
  }

  /**
   * Get RouterId
   *
   * @return \HSP\PageBundle\Entity\IPV6Router
   */
  public function getRouterId()
  {
    return $this->RouterId;
  }

  /**
   * Set CronRunId
   *
   * @param \HSP\PageBundle\Entity\IPV6CronRuns $cronRunId
   * @return IPV6CronLog
   */
  public function setCronRunId(\HSP\PageBundle\Entity\IPV6CronRuns $cronRunId = null)
  {
    $this->CronRunId = $cronRunId;

    return $this;
  }

  /**
   * Get CronRunId
   *
   * @return \HSP\PageBundle\Entity\IPV6CronRuns
   */
  public function getCronRunId()
  {
    return $this->CronRunId;
  }
}