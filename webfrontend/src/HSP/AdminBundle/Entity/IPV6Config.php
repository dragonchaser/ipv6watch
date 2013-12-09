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
  private $htaccessUserName;

  /**
   * @ORM\Column(type="string", nullable=true)
   */
  private $htaccessPassword;

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
   * @param mixed $htaccessPassword
   */
  public function setHtaccessPassword($htaccessPassword)
  {
    $this->htaccessPassword = $htaccessPassword;
  }

  /**
   * @return mixed
   */
  public function getHtaccessPassword()
  {
    return $this->htaccessPassword;
  }

  /**
   * @param mixed $htaccessUserName
   */
  public function setHtaccessUserName($htaccessUserName)
  {
    $this->htaccessUserName = $htaccessUserName;
  }

  /**
   * @return mixed
   */
  public function getHtaccessUserName()
  {
    return $this->htaccessUserName;
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

} 