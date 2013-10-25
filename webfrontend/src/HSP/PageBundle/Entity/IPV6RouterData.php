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
 * @ORM\Table(name="ipv6_routerdata", uniqueConstraints={@ORM\UniqueConstraint(name="ipv4addr",columns={"ipv4Address"})})
 */

class IPV6RouterData
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
    private $ipv6Address;

    /**
     * @ORM\Column(type="string");
     */
    private $macAddress;

    /**
     * @ORM\Column(type="string");
     */
    private $ipv4Address;

    /**
     * @ORM\Column(type="integer", length=1)
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="IPV6LogEntry", mappedBy="IPV6RouterData", cascade={"ALL"}, indexBy="IPV6LogEntry")
     */
    private $logentry;
}
