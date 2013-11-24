<?php
/**
 * This file ist published under GPLv3
 * Licence: http://www.gnu.org/licenses/gpl-3.0.txt
 * User: chaser
 * Date: 11/6/13
 * Time: 2:38 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_cronruns");
 */
class IPV6CronRuns
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\Column(type="datetime")
   */
  private $starttime;

  /**
   * @ORM\Column(type="datetime",options={"default" = NULL}, nullable=true)
   */
  private $endtime;

  /**
   * @ORM\ManyToMany(targetEntity="IPV6CronLog")
   * @ORM\JoinTable(name="ipv6_cron_runs_to_log",
   *      joinColumns={@ORM\JoinColumn(name="cronrun_id", referencedColumnName="id")},
   *      inverseJoinColumns={@ORM\JoinColumn(name="cronlog_id", referencedColumnName="cronRunId", unique=true)}
   *      )
   **/
  private $cronLogEntry;
} 