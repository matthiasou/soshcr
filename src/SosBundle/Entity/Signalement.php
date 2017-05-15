<?php

namespace SosBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Signalement
 *
 * @ORM\Table(name="signalement")
 * @ORM\Entity(repositoryClass="SosBundle\Repository\SignalementRepository")
 */
class Signalement
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
     * @ORM\Column(name="raison", type="text")
     */
    private $raison;

    /**
     * @var string
     *
     * @ORM\Column(name="signaleurPrenom", type="string", length=255)
     */
    private $signaleurPrenom;

    /**
     * @var string
     *
     * @ORM\Column(name="signaleurNom", type="string", length=255)
     */
    private $signaleurNom;

    /**
     * @var string
     *
     * @ORM\Column(name="signaleurEmail", type="string", length=255)
     */
    private $signaleurEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="signaleurTelephone", type="string", length=255)
     */
    private $signaleurTelephone;

    /**
     * @var string
     *
     * @ORM\Column(name="signale", type="string", length=255)
     */
    private $signale;


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
     * Set raison
     *
     * @param string $raison
     *
     * @return Signalement
     */
    public function setRaison($raison)
    {
        $this->raison = $raison;

        return $this;
    }

    /**
     * Get raison
     *
     * @return string
     */
    public function getRaison()
    {
        return $this->raison;
    }

    /**
     * Set signaleurPrenom
     *
     * @param string $signaleurPrenom
     *
     * @return Signalement
     */
    public function setSignaleurPrenom($signaleurPrenom)
    {
        $this->signaleurPrenom = $signaleurPrenom;

        return $this;
    }

    /**
     * Get signaleurPrenom
     *
     * @return string
     */
    public function getSignaleurPrenom()
    {
        return $this->signaleurPrenom;
    }

    /**
     * Set signaleurNom
     *
     * @param string $signaleurNom
     *
     * @return Signalement
     */
    public function setSignaleurNom($signaleurNom)
    {
        $this->signaleurNom = $signaleurNom;

        return $this;
    }

    /**
     * Get signaleurNom
     *
     * @return string
     */
    public function getSignaleurNom()
    {
        return $this->signaleurNom;
    }

    /**
     * Set signaleurEmail
     *
     * @param string $signaleurEmail
     *
     * @return Signalement
     */
    public function setSignaleurEmail($signaleurEmail)
    {
        $this->signaleurEmail = $signaleurEmail;

        return $this;
    }

    /**
     * Get signaleurEmail
     *
     * @return string
     */
    public function getSignaleurEmail()
    {
        return $this->signaleurEmail;
    }

    /**
     * Set signaleurTelephone
     *
     * @param string $signaleurTelephone
     *
     * @return Signalement
     */
    public function setSignaleurTelephone($signaleurTelephone)
    {
        $this->signaleurTelephone = $signaleurTelephone;

        return $this;
    }

    /**
     * Get signaleurTelephone
     *
     * @return string
     */
    public function getSignaleurTelephone()
    {
        return $this->signaleurTelephone;
    }

    /**
     * Set signale
     *
     * @param string $signale
     *
     * @return Signalement
     */
    public function setSignale($signale)
    {
        $this->signale = $signale;

        return $this;
    }

    /**
     * Get signale
     *
     * @return string
     */
    public function getSignale()
    {
        return $this->signale;
    }
}
