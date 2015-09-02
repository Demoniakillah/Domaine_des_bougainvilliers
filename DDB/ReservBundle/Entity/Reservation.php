<?php

namespace DDB\ReservBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DDB\ReservBundle\Entity\ReservationRepository")
 */
class Reservation
{
    public function __construct() {
        $this->date =new \DateTime();
        $this->debut =new \DateTime('+2 day');
        $this->fin = new \DateTime('+3 day');
        $this->validee=FALSE;
        $this->traitee=FALSE;
    }
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="transactionId", type="string", nullable=true)
     */
    private $transactionId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="datetime")
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="datetime")
     */
    private $fin;

    /**
     * @var integer
     *
     * @ORM\Column(name="bungalow_id", type="integer")
     * @ORM\OneToOne(targetEntity="DDB\AdminBundle\Entity\Bungalow", cascade={"persist"})
     */
    private $bungalow;

    /**
     * @var integer
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="validee", type="boolean")
     */
    private $validee;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="traitee", type="boolean")
     */
    private $traitee;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="cp", type="integer")
     */
    private $cp;
    
    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;
    
    /**
     * @var string
     *
     * @ORM\Column(name="civilite", type="string", length=4)
     */
    private $civilite;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="traiteeLe", type="datetime", nullable=true)
     */
    private $traiteeLe;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     * 
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

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
     * @ORM\Column(name="token", type="string", length=255)
     */
    private $token;
    

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
     * Set debut
     *
     * @param \DateTime $debut
     * @return Reservation
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     * @return Reservation
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime 
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     * @return Reservation
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return integer 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Reservation
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
     * Set nom
     *
     * @param string $nom
     * @return Reservation
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
     * Set prenom
     *
     * @param string $prenom
     * @return Reservation
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
     * Set adresse
     *
     * @param string $adresse
     * @return Reservation
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }



    /**
     * Set telephone
     *
     * @param string $telephone
     * @return Reservation
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
     * Set bungalow
     *
     * @param integer $bungalow
     * @return Reservation
     */
    public function setBungalow($bungalow)
    {
        $this->bungalow = $bungalow;

        return $this;
    }

    /**
     * Get bungalow
     *
     * @return integer 
     */
    public function getBungalow()
    {
        return $this->bungalow;
    }


    /**
     * Set cp
     *
     * @param integer $cp
     * @return Reservation
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return integer 
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set ville
     *
     * @param string $ville
     * @return Reservation
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Reservation
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
     * Set validee
     *
     * @param boolean $validee
     * @return Reservation
     */
    public function setValidee($validee)
    {
        $this->validee = $validee;

        return $this;
    }

    /**
     * Get validee
     *
     * @return boolean 
     */
    public function getValidee()
    {
        return $this->validee;
    }

    /**
     * Set civilite
     *
     * @param string $civilite
     * @return Reservation
     */
    public function setCivilite($civilite)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return string 
     */
    public function getCivilite()
    {
        return $this->civilite;
    }
    
    /**
     * Set traitee
     *
     * @param boolean $traitee
     * @return Reservation
     */
    public function setTraitee($traitee)
    {
        $this->traitee = $traitee;

        return $this;
    }

    /**
     * Get traitee
     *
     * @return boolean 
     */
    public function getTraitee()
    {
        return $this->traitee;
    }

    /**
     * Set traiteeLe
     *
     * @param \DateTime $traiteeLe
     * @return Reservation
     */
    public function setTraiteeLe($traiteeLe)
    {
        $this->traiteeLe = $traiteeLe;

        return $this;
    }

    /**
     * Get traiteeLe
     *
     * @return \DateTime 
     */
    public function getTraiteeLe()
    {
        return $this->traiteeLe;
    }
    


    /**
     * Set transactionId
     *
     * @param integer $transactionId
     * @return Reservation
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return integer 
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Reservation
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }
    
}
