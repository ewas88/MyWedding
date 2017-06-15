<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TravelInfo
 *
 * @ORM\Table(name="travel_info")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TravelInfoRepository")
 */
class TravelInfo
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
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @var string
     *
     * @ORM\Column(name="travel_type", type="string", length=255)
     */
    private $travelType;


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
     * Set price
     *
     * @param float $price
     *
     * @return TravelInfo
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set capacity
     *
     * @param integer $capacity
     *
     * @return TravelInfo
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    /**
     * Get capacity
     *
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * Set travelType
     *
     * @param string $travelType
     *
     * @return TravelInfo
     */
    public function setTravelType($travelType)
    {
        $this->travelType = $travelType;

        return $this;
    }

    /**
     * Get travelType
     *
     * @return string
     */
    public function getTravelType()
    {
        return $this->travelType;
    }
}

