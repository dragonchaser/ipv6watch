<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/20/13
 * Time: 3:22 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\Orm\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_ip_entry")
 */
class IPV6IpAddress
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $ipv6MacEntryId;
  /**
   * @ORM\Column(type="string")
   */
  private $ipAddress;

  /**
   * @ORM\Column(type="string", length=2)
   */
  private $addressType;

  /**
   * @return mixed
   */
  public function getVlanName()
  {
    return $this->vlanName;
  }

  /**
   * @return mixed
   */
  public function getRouterId()
  {
    return $this->RouterId;
  }

  /**
   * @return mixed
   */
  public function getAdded()
  {
    return $this->added;
  }

  /**
   * @return mixed
   */
  public function getAddressType()
  {
    return $this->addressType;
  }

  /**
   * @return mixed
   */
  public function getInterface()
  {
    return $this->interface;
  }

  /**
   * @return mixed
   */
  public function getIpAddress()
  {
    return $this->ipAddress;
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
   * @ORM\ManyToOne(targetEntity="IPV6Router")
   * @ORM\JoinColumn(name="routerId", referencedColumnName="id")
   */
  private $RouterId;

  /**
   * @ORM\Column(type="string")
   */
  private $vlanName;

  /**
   * @ORM\Column(type="string")
   */
  private $interface;

  /**
   * @ORM\Column(type="datetime")
   */
  private $added;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastseen;
}
