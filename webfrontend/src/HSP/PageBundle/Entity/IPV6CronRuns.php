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
 * @ORM\Table(name="ipv6_cronruns");
 */
class IPV6CronRuns
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="datetime")
   */
  private $starttime;

  /**
   * @ORM\Column(type="datetime",options={"default" = NULL}, nullable=true)
   */
  private $endtime;

  public function getRuntime()
  {
    return $this->getStarttime()->diff($this->getEndtime())->s;
  }

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
   * Set starttime
   *
   * @param \DateTime $starttime
   * @return IPV6CronRuns
   */
  public function setStarttime($starttime)
  {
    $this->starttime = $starttime;

    return $this;
  }

  /**
   * Get starttime
   *
   * @return \DateTime
   */
  public function getStarttime()
  {
    return $this->starttime;
  }

  /**
   * Set endtime
   *
   * @param \DateTime $endtime
   * @return IPV6CronRuns
   */
  public function setEndtime($endtime)
  {
    $this->endtime = $endtime;
    return $this;
  }

  /**
   * Get endtime
   *
   * @return \DateTime
   */
  public function getEndtime()
  {
    return $this->endtime;
  }
}