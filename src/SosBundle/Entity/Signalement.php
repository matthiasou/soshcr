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
     * @ORM\Column(name="proposition", type="text")
     */
    private $proposition;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomsignaleur", type="string", length=255)
     */
    private $prenomsignaleur;

    /**
     * @var string
     *
     * @ORM\Column(name="nomsignaleur", type="string", length=255)
     */
    private $nomsignaleur;

    /**
     * @var string
     *
     * @ORM\Column(name="emailsignaleur", type="string", length=255)
     */
    private $emailsignaleur;

    /**
     * @var string
     *
     * @ORM\Column(name="telephonesignaleur", type="string", length=255)
     */
    private $telephonesignaleur;





    /**
     * Get id
     *
     * @return integer
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
     * Set proposition
     *
     * @param string $proposition
     *
     * @return Signalement
     */
    public function setProposition($proposition)
    {
        $this->proposition = $proposition;

        return $this;
    }

    /**
     * Get proposition
     *
     * @return string
     */
    public function getProposition()
    {
        return $this->proposition;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Signalement
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Signalement
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Signalement
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
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Signalement
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set prenomsignaleur
     *
     * @param string $prenomsignaleur
     *
     * @return Signalement
     */
    public function setPrenomsignaleur($prenomsignaleur)
    {
        $this->prenomsignaleur = $prenomsignaleur;

        return $this;
    }

    /**
     * Get prenomsignaleur
     *
     * @return string
     */
    public function getPrenomsignaleur()
    {
        return $this->prenomsignaleur;
    }

    /**
     * Set nomsignaleur
     *
     * @param string $nomsignaleur
     *
     * @return Signalement
     */
    public function setNomsignaleur($nomsignaleur)
    {
        $this->nomsignaleur = $nomsignaleur;

        return $this;
    }

    /**
     * Get nomsignaleur
     *
     * @return string
     */
    public function getNomsignaleur()
    {
        return $this->nomsignaleur;
    }

    /**
     * Set emailsignaleur
     *
     * @param string $emailsignaleur
     *
     * @return Signalement
     */
    public function setEmailsignaleur($emailsignaleur)
    {
        $this->emailsignaleur = $emailsignaleur;

        return $this;
    }

    /**
     * Get emailsignaleur
     *
     * @return string
     */
    public function getEmailsignaleur()
    {
        return $this->emailsignaleur;
    }

    /**
     * Set telephonesignaleur
     *
     * @param string $telephonesignaleur
     *
     * @return Signalement
     */
    public function setTelephonesignaleur($telephonesignaleur)
    {
        $this->telephonesignaleur = $telephonesignaleur;

        return $this;
    }

    /**
     * Get telephonesignaleur
     *
     * @return string
     */
    public function getTelephonesignaleur()
    {
        return $this->telephonesignaleur;
    }
}
