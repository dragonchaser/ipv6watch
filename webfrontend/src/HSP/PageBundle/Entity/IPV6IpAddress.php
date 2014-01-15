<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/20/13
 * Time: 3:22 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_ipaddress")
 */
class IPV6IpAddress
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="IDENTITY")
   */
  private $id;
  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $ipv4Address;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $ipv6Address;

  /**
   * @ORM\Column(type="string")
   */
  private $macAddress;

  /**
   * @ORM\Column(type="datetime")
   */
  private $added;

  /**
   * @ORM\Column(type="datetime")
   */
  private $lastseen;


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
   * Set ipv4Address
   *
   * @param string $ipv4Address
   * @return IPV6IpAddress
   */
  public function setIpv4Address($ipv4Address)
  {
    $this->ipv4Address = $ipv4Address;

    return $this;
  }

  /**
   * Get ipv4Address
   *
   * @return string
   */
  public function getIpv4Address()
  {
    return $this->ipv4Address;
  }

  /**
   * Set ipv6Address
   *
   * @param string $ipv6Address
   * @return IPV6IpAddress
   */
  public function setIpv6Address($ipv6Address)
  {
    $this->ipv6Address = $ipv6Address;

    return $this;
  }

  /**
   * Get ipv6Address
   *
   * @return string
   */
  public function getIpv6Address()
  {
    return $this->ipv6Address;
  }

  /**
   * Set macAddress
   *
   * @param string $macAddress
   * @return IPV6IpAddress
   */
  public function setMacAddress($macAddress)
  {
    $this->macAddress = $macAddress;

    return $this;
  }

  /**
   * Get macAddress
   *
   * @return string
   */
  public function getMacAddress()
  {
    return $this->macAddress;
  }

  /**
   * Set added
   *
   * @param \DateTime $added
   * @return IPV6IpAddress
   */
  public function setAdded($added)
  {
    $this->added = $added;

    return $this;
  }

  /**
   * Get added
   *
   * @return \DateTime
   */
  public function getAdded()
  {
    return $this->added;
  }

  /**
   * Set lastseen
   *
   * @param \DateTime $lastseen
   * @return IPV6IpAddress
   */
  public function setLastseen($lastseen)
  {
    $this->lastseen = $lastseen;

    return $this;
  }

  /**
   * Get lastseen
   *
   * @return \DateTime
   */
  public function getLastseen()
  {
    return $this->lastseen;
  }
}