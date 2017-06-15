<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * WeddingInfo
 *
 * @ORM\Table(name="wedding_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WeddingInfoRepository")
 */
class WeddingInfo
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
     * @var \DateTime
     *
     * @ORM\Column(name="wedding_date", type="datetime")
     */
    private $weddingDate;

    /**
     * @var string
     *
     * @ORM\Column(name="bride_name", type="string", length=255)
     */
    private $brideName;

    /**
     * @var string
     *
     * @ORM\Column(name="groom_name", type="string", length=255)
     */
    private $groomName;

    /**
     * @var string
     *
     * @ORM\Column(name="church_name", type="string", length=255)
     */
    private $churchName;

    /**
     * @var string
     *
     * @ORM\Column(name="church_address", type="string", length=255)
     */
    private $churchAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="venue_name", type="string", length=255)
     */
    private $venueName;

    /**
     * @var string
     *
     * @ORM\Column(name="venue_address", type="string", length=255)
     */
    private $venueAddress;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_transport", type="boolean")
     */
    private $isTransport;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_accomodation", type="boolean")
     */
    private $isAccomodation;

    /**
     * @var float
     *
     * @ORM\Column(name="budget", type="float")
     */
    private $budget;


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
     * Set weddingDate
     *
     * @param \DateTime $weddingDate
     *
     * @return WeddingInfo
     */
    public function setWeddingDate($weddingDate)
    {
        $this->weddingDate = $weddingDate;

        return $this;
    }

    /**
     * Get weddingDate
     *
     * @return \DateTime
     */
    public function getWeddingDate()
    {
        return $this->weddingDate;
    }

    /**
     * Set brideName
     *
     * @param string $brideName
     *
     * @return WeddingInfo
     */
    public function setBrideName($brideName)
    {
        $this->brideName = $brideName;

        return $this;
    }

    /**
     * Get brideName
     *
     * @return string
     */
    public function getBrideName()
    {
        return $this->brideName;
    }

    /**
     * Set groomName
     *
     * @param string $groomName
     *
     * @return WeddingInfo
     */
    public function setGroomName($groomName)
    {
        $this->groomName = $groomName;

        return $this;
    }

    /**
     * Get groomName
     *
     * @return string
     */
    public function getGroomName()
    {
        return $this->groomName;
    }

    /**
     * Set churchName
     *
     * @param string $churchName
     *
     * @return WeddingInfo
     */
    public function setChurchName($churchName)
    {
        $this->churchName = $churchName;

        return $this;
    }

    /**
     * Get churchName
     *
     * @return string
     */
    public function getChurchName()
    {
        return $this->churchName;
    }

    /**
     * Set churchAddress
     *
     * @param string $churchAddress
     *
     * @return WeddingInfo
     */
    public function setChurchAddress($churchAddress)
    {
        $this->churchAddress = $churchAddress;

        return $this;
    }

    /**
     * Get churchAddress
     *
     * @return string
     */
    public function getChurchAddress()
    {
        return $this->churchAddress;
    }

    /**
     * Set venueName
     *
     * @param string $venueName
     *
     * @return WeddingInfo
     */
    public function setVenueName($venueName)
    {
        $this->venueName = $venueName;

        return $this;
    }

    /**
     * Get venueName
     *
     * @return string
     */
    public function getVenueName()
    {
        return $this->venueName;
    }

    /**
     * Set venueAddress
     *
     * @param string $venueAddress
     *
     * @return WeddingInfo
     */
    public function setVenueAddress($venueAddress)
    {
        $this->venueAddress = $venueAddress;

        return $this;
    }

    /**
     * Get venueAddress
     *
     * @return string
     */
    public function getVenueAddress()
    {
        return $this->venueAddress;
    }

    /**
     * Set isTransport
     *
     * @param boolean $isTransport
     *
     * @return WeddingInfo
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
     * @return WeddingInfo
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

    /**
     * Set budget
     *
     * @param float $budget
     *
     * @return WeddingInfo
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;

        return $this;
    }

    /**
     * Get budget
     *
     * @return float
     */
    public function getBudget()
    {
        return $this->budget;
    }
}

