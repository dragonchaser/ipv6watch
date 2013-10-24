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
class Timelog
{
    /**
     * @ORM\id
     * @ORM\Column(type="integer")
     */
    protected $id;
    /**
     * @ORM\Column(type="datetime")
     * @ORM\ManyToOne(targetEntity="Logentry", inversedBy="Timelog")
     */
    protected $lastseen;
}