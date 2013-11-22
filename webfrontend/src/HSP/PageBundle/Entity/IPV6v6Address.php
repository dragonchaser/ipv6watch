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
 * @ORM\Table(name="ipv6_ipv6_entry")
 */
class IPV6v6Address {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
 	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $ipv6LogEntryId;
	/**
	 * @ORM\Column(type="string")
	 */
	private $ipv6Address;

	/**
	 * @return mixed
	 */
	public function getIpv6Address()
	{
		return $this->ipv6Address;
	}

	/**
	 * @return mixed
	 */
	public function getIpv6LogEntryId()
	{
		return $this->ipv6LogEntryId;
	}
}
