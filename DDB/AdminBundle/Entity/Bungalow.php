<?php

namespace DDB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bungalow
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DDB\AdminBundle\Entity\BungalowRepository")
 */
class Bungalow
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer
     * @ORM\Column(name="numero", type="integer", unique=true)
     */
    private $numero;
    
    /**
     *@ORM\Column(name="texte", type="text")
     */
    private $texte;
    
    /**
     * @ORM\Column(name="resume", type="text")
     */
    private $resume;
    
    /**
     * @ORM\Column(name="nom", type="string", length=255, unique=true)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="tarif", type="integer")
     */
    private $tarif;

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
     * Set tarif
     *
     * @param integer $tarif
     * @return Bungalow
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return integer 
     */
    public function getTarif()
    {
        return $this->tarif;
    }

    public function __construct() {
        $this->photos = array();
    }

    /**
     * Set numero
     *
     * @param integer $numero
     * @return Bungalow
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }


    /**
     * Set texte
     *
     * @param string $texte
     * @return Bungalow
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;

        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set resume
     *
     * @param string $resume
     * @return Bungalow
     */
    public function setResume($resume)
    {
        $this->resume = $resume;

        return $this;
    }

    /**
     * Get resume
     *
     * @return string 
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Bungalow
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

}
