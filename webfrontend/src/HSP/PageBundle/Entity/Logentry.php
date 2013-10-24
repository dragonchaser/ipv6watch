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
 * @ORM\Table(name="ipv6_logentry")
 */
class Logentry
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $ipv6Address;

    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $macAddress;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_added;

    /**
     * @ORM\OneToMany(targetEntity="Timelog", mappedBy="Logentry", cascade={"ALL"}, indexBy="Timelog")
     */
    private $Timelog;
}
