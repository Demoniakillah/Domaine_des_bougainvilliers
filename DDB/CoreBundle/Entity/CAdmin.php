<?php

namespace DDB\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

    /*
     * CAdmin
     * 
     * @ORM\Entity
     */
class CAdmin{
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="varchar", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="varchar", length=255)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="varchar", length=255)
     */
    private $objet;
    
    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;
    
    public function __construct(){
        $this->name = '@nonymous';
        $this->email = 'no@reply.to';
        $this->objet = 'Demande de contact';
        $this->message = "Votre message";
}
    
    function getObjet() {
        return $this->objet;
    }

    /**
     * Set objet
     *
     * @param string $objet
     * @return CAdmin
     */
    function setObjet($objet) {
        $this->objet = $objet;
    }

    /**
     * Get name
     *
     * @return string 
     */
        function getName() {
        return $this->name;
    }

    /**
     * Get email
     *
     * @return string 
     */
    function getEmail() {
        return $this->email;
    }

    /**
     * Get message
     *
     * @return string 
     */
    function getMessage() {
        return $this->message;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return CAdmin
     */
    function setName($name) {
        $this->name = $name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return CAdmin
     */
    function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return CAdmin
     */
    function setMessage($message) {
        $this->message = $message;
    }


}