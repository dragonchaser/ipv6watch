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
 * @ORM\Table(name="ipv6_cronlog");
 */
class IPV6CronLog
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	private $cronid;

	/**
	 * @ORM\Column(type="datetime");
	 */
	private $time;
	/**
	 * @ORM\Column(type="integer",options={"default" = NULL}, nullable=true);
	 * @ORM\ManyToOne(targetEntity="IPV6CronRuns", inversedBy="IPV6CronLog")
	 */
	private $routerid;
	/**
	 * @ORM\Column(type="integer");
	 */
	private $type;
	/**
	 * @ORM\Column(type="string");
	 */
	private $logentry;
}