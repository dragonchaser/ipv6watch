<?php
/**
 * Created by PhpStorm.
 * User: chaser
 * Date: 10/25/13
 * Time: 12:24 PM
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
   * @ORM\Column(type="integer", length=1)
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
