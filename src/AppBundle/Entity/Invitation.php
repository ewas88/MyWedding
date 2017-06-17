<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Invitation
 *
 * @ORM\Table(name="invitation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="Present", mappedBy="invitation")
     */
    private $presents;

    public function __construct()
    {
        $this->presents = new ArrayCollection();
        $this->guests = new ArrayCollection();
    }

    /**
     * @ORM\OneToMany(targetEntity="Guest", mappedBy="invitation")
     */
    private $guests;

    /**
     * @return mixed
     */
    public function getPresents()
    {
        return $this->presents;
    }

    /**
     * @param mixed $presents
     */
    public function setPresents($presents)
    {
        $this->presents = $presents;
    }

    /**
     * @return mixed
     */
    public function getGuests()
    {
        return $this->guests;
    }

    /**
     * @param mixed $guests
     */
    public function setGuests($guests)
    {
        $this->guests = $guests;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Invitation
     */
    public function setCode()
    {
        $digits = str_split('abcdefghijklmnopqrstuvwxyz0123456789');
        shuffle($digits);
        $code = implode($digits);
        $this->code = substr($code, 0, 5);
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}

