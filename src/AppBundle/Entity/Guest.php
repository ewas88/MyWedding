<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Guest
 *
 * @ORM\Table(name="guest")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GuestRepository")
 */
class Guest
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=255)
     */
    private $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_single", type="boolean", nullable=true)
     */
    private $isSingle;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_confirmed", type="boolean", nullable=true)
     */
    private $isConfirmed;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_coming", type="boolean", nullable=true)
     */
    private $isComing;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_transport", type="boolean", nullable=true)
     */
    private $isTransport;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_accomodation", type="boolean", nullable=true)
     */
    private $isAccomodation;

    /**
     * @ORM\ManyToOne(targetEntity="Invitation", inversedBy="guests")
     * @ORM\JoinColumn(name="invite_id", referencedColumnName="id", nullable=true)
     */
    private $invitation;

    /**
     * @return mixed
     */
    public function getInvitation()
    {
        return $this->invitation;
    }

    /**
     * @param mixed $invitation
     */
    public function setInvitation($invitation)
    {
        $this->invitation = $invitation;
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
     * Set name
     *
     * @param string $name
     *
     * @return Guest
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return Guest
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Guest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set isSingle
     *
     * @param boolean $isSingle
     *
     * @return Guest
     */
    public function setIsSingle($isSingle)
    {
        $this->isSingle = $isSingle;

        return $this;
    }

    /**
     * Get isSingle
     *
     * @return bool
     */
    public function getIsSingle()
    {
        return $this->isSingle;
    }

    /**
     * Set isConfirmed
     *
     * @param boolean $isConfirmed
     *
     * @return Guest
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;

        return $this;
    }

    /**
     * Get isConfirmed
     *
     * @return bool
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * Set isComing
     *
     * @param boolean $isComing
     *
     * @return Guest
     */
    public function setIsComing($isComing)
    {
        $this->isComing = $isComing;

        return $this;
    }

    /**
     * Get isComing
     *
     * @return bool
     */
    public function getIsComing()
    {
        return $this->isComing;
    }

    /**
     * Set isTransport
     *
     * @param boolean $isTransport
     *
     * @return Guest
     */
    public function setIsTransport($isTransport)
    {
        $this->isTransport = $isTransport;

        return $this;
    }

    /**
     * Get isTransport
     *
     * @return bool
     */
    public function getIsTransport()
    {
        return $this->isTransport;
    }

    /**
     * Set isAccomodation
     *
     * @param boolean $isAccomodation
     *
     * @return Guest
     */
    public function setIsAccomodation($isAccomodation)
    {
        $this->isAccomodation = $isAccomodation;

        return $this;
    }

    /**
     * Get isAccomodation
     *
     * @return bool
     */
    public function getIsAccomodation()
    {
        return $this->isAccomodation;
    }
}

