<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 12/9/13
 * Time: 7:05 AM
 */

namespace HSP\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_config");
 */
class IPV6Config
{
  /**
   * @ORM\Id
   * @ORM\Column(type="string")
   */
  private $configInstanceName;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $securityToken;

  /**
   * @ORM\Column(type="boolean", nullable=true)
   */

  private $enableExports;
  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $logPruningTime;

  /**
   * @ORM\Column(type="integer", nullable=true)
   */
  private $maxExportItems;

  /**
   * @ORM\Column(type="boolean", nullable=true)
   */
  private $enablePruning;

  /**
   * @param mixed $configInstanceName
   */
  public function setConfigInstanceName($configInstanceName)
  {
    $this->configInstanceName = $configInstanceName;
  }

  /**
   * @return mixed
   */
  public function getConfigInstanceName()
  {
    return $this->configInstanceName;
  }

  /**
   * @param mixed $securityToken
   */
  public function setSecurityToken($securityToken)
  {
    $this->securityToken = $securityToken;
  }

  /**
   * @return mixed
   */
  public function getSecurityToken()
  {
    return $this->securityToken;
  }

  /**
   * @param mixed $logPruningTime
   */
  public function setLogPruningTime($logPruningTime)
  {
    $this->logPruningTime = $logPruningTime;
  }

  /**
   * @return mixed
   */
  public function getLogPruningTime()
  {
    return $this->logPruningTime;
  }

  /**
   * @param mixed $enableExports
   */
  public function setEnableExports($enableExports)
  {
    $this->enableExports = $enableExports;
  }

  /**
   * @return mixed
   */
  public function getEnableExports()
  {
    return $this->enableExports;
  }

  /**
   * @param mixed $maxExportItems
   */
  public function setMaxExportItems($maxExportItems)
  {
    $this->maxExportItems = $maxExportItems;
  }

  /**
   * @return mixed
   */
  public function getMaxExportItems()
  {
    return $this->maxExportItems;
  }


  /**
   * Set enablePruning
   *
   * @param boolean $enablePruning
   * @return IPV6Config
   */
  public function setEnablePruning($enablePruning)
  {
    $this->enablePruning = $enablePruning;

    return $this;
  }

  /**
   * Get enablePruning
   *
   * @return boolean
   */
  public function getEnablePruning()
  {
    return $this->enablePruning;
  }
}