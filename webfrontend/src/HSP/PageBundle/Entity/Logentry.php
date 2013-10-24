<?php
/**
 * Created by PhpStorm.
 * User: chaser
 * Date: 10/24/13
 * Time: 2:59 PM
 */

namespace HSP\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ipv6_logentry", uniqueConstraints={@ORM\UniqueConstraint(name="macToipv6", columns={"ipv6Address", "macAddress"})})
 */
class Logentry
{
    /**
     * @ORM\Id
     * @Orm\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $ipv6Address;

    /**
     * @ORM\Column(type="string")
     */
    private $macAddress;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_added;

    /**
     * @ORM\OneToMany(targetEntity="Timelog", mappedBy="Logentry", cascade={"ALL"}, indexBy="Timelog")
     */
    private $Timelog;

    public function setIpv6Address($ipv6Address)
    {
        $this->ipv6Address = $ipv6Address;
    }

    public function setMacAddress($macAddress)
    {
        $this->macAddress = $macAddress;
    }

    public function setDateAdded()
    {
        $this->date_added = new \DateTime('now');
    }

    public function getIpv6Address()
    {
        return $this->ipv6Address;
    }

    public function getMacAddress()
    {
        return $this->macAddress;
    }

    public function getDateAdded()
    {
        return $this->date_added;
    }
}
