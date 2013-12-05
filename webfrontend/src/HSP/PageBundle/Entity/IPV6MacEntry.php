<?php
/**
 * User: chaser
 * Date: 10/24/13
 * Time: 2:59 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_macentry")
 */
class IPV6MacEntry
{
  /**
   * @ORM\Id
   * @Orm\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=12)
   */
  private $macAddress;

  /**
   * @return mixed
   */
  public function getMacAddress()
  {
    return $this->macAddress;
  }

  /**
   * @return mixed
   */
  public function getRouterId()
  {
    return $this->RouterId;
  }

  /**
   * @ORM\ManyToMany(targetEntity="IPV6IpAddress")
   * @ORM\JoinTable(name="ipv6_logentry_to_ipaddress",
   *      joinColumns={@ORM\JoinColumn(name="logentryId", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="ipAddressId", referencedColumnName="ipv6MacEntryId", unique=true)}
   *      )
   **/
  private $ipv4Addresses;
  /**
   * @ORM\Column(type="datetime")
   */
  private $dateAdded;

  /**
   * @ORM\ManyToMany(targetEntity="IPV6TimeLog")
   * @ORM\JoinTable(name="ipv6_logentry_to_timelog",
   *      joinColumns={@ORM\JoinColumn(name="logentryId", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="timelogId", referencedColumnName="ipv6MacEntryId", unique=true)}
   *      )
   **/
  private $Timelog;

  /**
   * @ORM\ManyToOne(targetEntity="IPV6Router")
   * @ORM\JoinColumn(name="routerId", referencedColumnName="id")
   */
  private $RouterId;

  /**
   * @return mixed
   */
  public function getIpv4Addresses()
  {
    return $this->ipv4Addresses;
  }

  /**
   * @return mixed
   */
  public function getTimelog()
  {
    return $this->Timelog;
  }

  /**
   * @return mixed
   */
  public function getDateAdded()
  {
    return $this->dateAdded;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @return mixed
   */
  public function getIpv6Addresses()
  {
    return $this->ipv6Addresses;
  }
}
