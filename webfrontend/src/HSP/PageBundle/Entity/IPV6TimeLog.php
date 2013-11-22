<?php
/**
 * Created by PhpStorm.
 * User: chaser
 * Date: 10/24/13
 * Time: 4:21 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_timelog")
 */
class IPV6TimeLog
{
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $ipv6LogEntryId;
	/**
	 * @ORM\Column(type="datetime")
	 */
	private $lastseen;

	/**
	 * @ORM\Column(type="integer", length=1, options={"default"=0})
	 */
	private $hasBeenExported;

	/**
	 * @return mixed
	 */
	public function getHasBeenExported()
	{
		return $this->hasBeenExported;
	}

	/**
	 * @return mixed
	 */
	public function getIpv6LogEntryId()
	{
		return $this->ipv6LogEntryId;
	}

	/**
	 * @return mixed
	 */
	public function getLastseen()
	{
		return $this->lastseen;
	}

}