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
 * @ORM\Table(name="ipv6_logentry")
 */
class IPV6LogEntry
{
  /**
   * @ORM\Id
   * @Orm\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string", length=15)
   */
  private $ipv4Address;

  /**
   * @ORM\Column(type="string", length=12)
   */
  private $macAddress;

  /**
   * @ORM\ManyToMany(targetEntity="IPV6v6Address")
   * @ORM\JoinTable(name="ipv6_logenty_to_ipv6address",
   *      joinColumns={@ORM\JoinColumn(name="logentry_id", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="ipv6Address_id", referencedColumnName="ipv6LogEntryId", unique=true)}
   *      )
   **/
  private $ipv6Addresses;
  /**
   * @ORM\Column(type="datetime")
   */
  private $date_added;

  /**
   * @ORM\ManyToOne(targetEntity="IPV6Router")
   * @ORM\JoinColumn(name="IPV6Router", referencedColumnName="ipv6LogEntryId")
   */
  private $Router;

  /**
   * @ORM\ManyToMany(targetEntity="IPV6TimeLog")
   * @ORM\JoinTable(name="ipv6_logentry_to_timelog",
   *      joinColumns={@ORM\JoinColumn(name="logentry_id", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="timelog_id", referencedColumnName="ipv6LogEntryId", unique=true)}
   *      )
   **/
  private $Timelog;

  /**
   * @return mixed
   */
  public function getRouter()
  {
    return $this->Router;
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
    return $this->date_added;
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
  public function getIpv4Address()
  {
    return $this->ipv4Address;
  }

  /**
   * @return mixed
   */
  public function getIpv6Addresses()
  {
    return $this->ipv6Addresses;
  }

  /**
   * @return mixed
   */
  public function getMacAddress()
  {
    return $this->macAddress;
  }

}
