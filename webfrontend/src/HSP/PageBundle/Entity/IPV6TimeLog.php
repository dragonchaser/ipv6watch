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
     * @ORM\id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="datetime")
     */
    private $lastseen;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="IPV6LogEntry", inversedBy="IPV6TimeLog")
     */
    private $logentry;

    // commented out since all data is written by the client
    /*
    public function setLastSeen()
    {
        $this->lastseen = new \DateTime('now');
    }
    */

    function getLastSeen()
    {
        return $this->lastseen;
    }
}