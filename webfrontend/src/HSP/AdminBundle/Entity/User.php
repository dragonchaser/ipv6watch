<?php

namespace HSP\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="integer", length=1, options={"default"=0})
	 */
	private $isNotDeleteable;

	/**
	 * @ORM\Column(type="string")
	 */
	private $realName;

	/**
	 * @param mixed $realName
	 */
	public function setRealName($realName)
	{
		$this->realName = $realName;
	}

	/**
	 * @return mixed
	 */
	public function getRealName()
	{
		return $this->realName;
	}

	public function __construct() {
		parent::__construct ();
        $this->roles = array('ROLE_ADMIN');
    }

	/**
	 * @return mixed
	 */
	public function getIsNotDeleteable()
	{
		return $this->isNotDeleteable;
	}

	public function setIsNotDeleteable($value)
	{
		if ($value === 0) $this->isNotDeleteable = 0;
		else $this->isNotDeleteable = 1;
	}

}