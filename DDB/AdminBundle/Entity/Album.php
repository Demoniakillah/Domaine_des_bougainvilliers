<?php

namespace DDB\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Album
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="DDB\AdminBundle\Entity\AlbumRepository")
 */
class Album
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
     * @ORM\Column(name="position", type="integer")
     */
    private $position;
    
    private $file;


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
     * Set position
     *
     * @param string $position
     * @return Album
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set url
     *
     * @param integer $url
     * @return Album
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return integer 
     */
    public function getUrl()
    {
        return $this->url;
    }

    
    public function monter(){
        $this->position++;
    }
    
    public function descendre(){
        $this->position--;
    }
    
    public function getFile()
  {
    return $this->file;
  }
  
  public function setFile(UploadedFile $file=null)
  {
      $this->file=$file;
  }
  
    public function getUploadDir()
  {
    return 'img';
  }
  
  protected function getUploadRootDir()
  {
    return __DIR__.'/../../../../web/'.$this->getUploadDir();
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

    /**
     * Set description
     *
     * @param string $description
     * @return Album
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
