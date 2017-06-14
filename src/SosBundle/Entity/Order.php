<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="orders")
 * @ORM\Entity
 */
class Order
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /** @ORM\Column(type="decimal", precision=10, scale=5) */
    private $amount;
    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    public $date;

    /**
     * @var string
     *
     * @ORM\Column(name="idpayplug", type="string", length=255, unique=true)
     */
    public $idpayplug;

    /**
     * @ORM\Column(name="isvalide", type="boolean", nullable=true)
     */
    private $isvalide;


    public function getId()
    {
        return $this->id;
    }

    public function getAmount()
    {
        return $this->amount;
    }
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Set user
     *
     * @param \SosBundle\Entity\User $user
     *
     * @return Order
     */
    public function setUser(\SosBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \SosBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Order
     */
    public function setDate($date)
    {      
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Set idpayplug
     *
     * @param string $idpayplug
     *
     * @return Order
     */
    public function setIdpayplug($idpayplug)
    {
        $this->idpayplug = $idpayplug;

        return $this;
    }

    /**
     * Get idpayplug
     *
     * @return string
     */
    public function getIdpayplug()
    {
        return $this->idpayplug;
    }

    /**
     * Set isvalide
     *
     * @param boolean $isvalide
     *
     * @return Order
     */
    public function setIsvalide($isvalide)
    {
        $this->isvalide = $isvalide;

        return $this;
    }

    /**
     * Get isvalide
     *
     * @return boolean
     */
    public function getIsvalide()
    {
        return $this->isvalide;
    }

}