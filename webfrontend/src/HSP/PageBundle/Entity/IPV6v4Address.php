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
 * @ORM\Table(name="ipv6_ipv4_entry")
 */
class IPV6v4Address
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
  private $ipv4Address;

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
   * @return mixed
   */
  public function getIpv6Address()
  {
    return $this->ipv4Address;
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
  public function getRouterId()
  {
    return $this->RouterId;
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
  public function getIpv4Address()
  {
    return $this->ipv4Address;
  }

  /**
   * @return mixed
   */
  public function getVlanName()
  {
    return $this->vlanName;
  }
}
