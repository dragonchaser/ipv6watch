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
 * @ORM\Table(name=ipv6_ipv4_entry)
 */
class IPV6Ipv4Entry {

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string")
	 */
	private $ipv4Address;

	/**
	 * @ORM\OneToMany(targetEntity="IPV6LogEntry", mappedBy="IPV6Ipv4Entry", cascade={"ALL"}, indexBy="IPV6LogEntry")
	 */
	private $logEntry;
}
