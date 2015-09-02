<?php

namespace DDB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Photo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DDB\AdminBundle\Entity\PhotoRepository")
 */
class Photo
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
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="bungalow_id", type="integer")
     */
    private $bungalowId;
    
    
    /**
     *
     * @Assert\File(mimeTypes="image/jpeg" , mimeTypesMessage="Uniquement fichier JPEG", maxSize="2M", maxSizeMessage="Le fichier est trop volumineux ({{ size }}Mo). La taille maximale autorisée est {{ limit }}Mo")
     */
    private $file;
    
    public function getFile()
  {
    return $this->file;
  }
  
  public function setFile(UploadedFile $file=null)
  {
      $this->file=$file;
  }
    
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
     * Set url
     *
     * @param string $url
     * @return Photo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set bungalowId
     *
     * @param integer $bungalowId
     * @return Photo
     */
    public function setBungalowId($bungalowId)
    {
        $this->bungalowId = $bungalowId;

        return $this;
    }

    /**
     * Get bungalowId
     *
     * @return integer 
     */
    public function getBungalowId()
    {
        return $this->bungalowId;
    }
    
    public function upload(){
        if(null===$this->file){
            return;
        } 
        $name = md5(uniqid(rand(), true)).'.JPG'; 
        $this->file->move($this->getUploadRootDir(), $name);
        $this->url = $name;
        $this->alt = $name;
    }
    
    public function getUploadDir()
  {
    return 'img';
  }
  
  protected function getUploadRootDir()
  {
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
  }

    /**
     * Set description
     *
     * @param string $description
     * @return Photo
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
}
