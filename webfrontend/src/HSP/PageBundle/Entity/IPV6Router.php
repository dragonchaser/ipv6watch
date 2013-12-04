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
 * @ORM\Table(name="ipv6_routerdata")
 */

class IPV6Router
{
  /**
   * @ORM\id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="string")
   */
  private $routerName;

  /**
   * @ORM\Column(type="string");
   */
  private $fqdn;

  /**
   * @ORM\Column(type="integer", options={"default"=22})
   */
  private $port;

  /**
   * @ORM\Column(type="boolean")
   */
  private $active;

  /**
   * @ORM\Column(type="string")
   */
  private $username;

  /**
   * @ORM\Column(type="string")
   */
  private $password;

  /**
   * @ORM\Column(type="text")
   */
  private $sshKey;

  /**
   * @param mixed $port
   */
  public function setPort($port)
  {
    $this->port = $port;
  }

  /**
   * @return mixed
   */
  public function getPort()
  {
    return $this->port;
  }

  /**
   * @param mixed $sshKey
   */
  public function setSshKey($sshKey)
  {
    $this->sshKey = $sshKey;
  }

  /**
   * @return mixed
   */
  public function getSshKey()
  {
    return $this->sshKey;
  }

  /**
   * @return mixed
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * @param mixed $username
   */
  public function setUsername($username)
  {
    $this->username = $username;
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @param mixed $routerName
   */
  public function setRouterName($routerName)
  {
    $this->routerName = $routerName;
  }

  /**
   * @return mixed
   */
  public function getRouterName()
  {
    return $this->routerName;
  }

  /**
   * @param mixed $password
   */
  public function setPassword($password)
  {
    $this->password = $password;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @param mixed $macAddress
   */
  public function setMacAddress($macAddress)
  {
    $this->macAddress = $macAddress;
  }

  /**
   * @return mixed
   */
  public function getMacAddress()
  {
    return $this->macAddress;
  }

  /**
   * @param mixed $logentry
   */
  public function setLogentry($logentry)
  {
    $this->logentry = $logentry;
  }

  /**
   * @return mixed
   */
  public function getLogentry()
  {
    return $this->logentry;
  }

  /**
   * @param mixed $ipv6Address
   */
  public function setIpv6Address($ipv6Address)
  {
    $this->ipv6Address = $ipv6Address;
  }

  /**
   * @return mixed
   */
  public function getIpv6Address()
  {
    return $this->ipv6Address;
  }

  /**
   * @param mixed $ipv4Address
   */
  public function setFqdn($ipv4Address)
  {
    $this->fqdn = $ipv4Address;
  }

  /**
   * @return mixed
   */
  public function getFqdn()
  {
    return $this->fqdn;
  }

  /**
   * @param mixed $active
   */
  public function setActive($active)
  {
    $this->active = $active;
  }

  /**
   * @return mixed
   */
  public function getActive()
  {
    return $this->active;
  }
}
