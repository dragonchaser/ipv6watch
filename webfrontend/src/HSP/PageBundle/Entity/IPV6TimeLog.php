<?php
/**
 * Created by PhpStorm.
 * User: chaser
 * Date: 10/24/13
 * Time: 4:21 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_timelog")
 */
class IPV6TimeLog
{
  public function __construct()
  {
    $this->ipId = new ArrayCollection();
  }

  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="IPV6IpAddress")
   * @ORM\JoinColumn(name="ipId", referencedColumnName="id")
   */
  private $ipId;

  /**
   * @ORM\Column(type="string")
   */
  private $interface;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastseen;

  /**
   * @ORM\Column(type="integer", length=1, options={"default"=0})
   */
  private $hasBeenExported;

  /**
   * @ORM\ManyToMany(targetEntity="IPV6Router")
   * @ORM\JoinColumn(name="routerId", referencedColumnName="id")
   */
  private $RouterId;

  /**
   * @return mixed
   */
  public function getHasBeenExported()
  {
    return $this->hasBeenExported;
  }

  /**
   * @return mixed
   */
  public function getIpv6MacEntryId()
  {
    return $this->ipv6MacEntryId;
  }

  /**
   * @return mixed
   */
  public function getLastseen()
  {
    return $this->lastseen;
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
   * Set interface
   *
   * @param string $interface
   * @return IPV6TimeLog
   */
  public function setInterface($interface)
  {
    $this->interface = $interface;

    return $this;
  }

  /**
   * Get interface
   *
   * @return string
   */
  public function getInterface()
  {
    return $this->interface;
  }

  /**
   * Set lastseen
   *
   * @param \DateTime $lastseen
   * @return IPV6TimeLog
   */
  public function setLastseen($lastseen)
  {
    $this->lastseen = $lastseen;

    return $this;
  }

  /**
   * Set hasBeenExported
   *
   * @param integer $hasBeenExported
   * @return IPV6TimeLog
   */
  public function setHasBeenExported($hasBeenExported)
  {
    $this->hasBeenExported = $hasBeenExported;

    return $this;
  }

  /**
   * Set ipId
   *
   * @param \HSP\PageBundle\Entity\IPV6IpAddress $ipId
   * @return IPV6TimeLog
   */
  public function setIpId(\HSP\PageBundle\Entity\IPV6IpAddress $ipId = null)
  {
    $this->ipId = $ipId;

    return $this;
  }

  /**
   * Get ipId
   *
   * @return \HSP\PageBundle\Entity\IPV6IpAddress
   */
  public function getIpId()
  {
    return $this->ipId;
  }

  /**
   * Set RouterId
   *
   * @param \HSP\PageBundle\Entity\IPV6Router $routerId
   * @return IPV6TimeLog
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
}