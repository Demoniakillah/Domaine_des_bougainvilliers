<?php

namespace DDB\CoreBundle\Entity;

class LivredorRepository extends \Doctrine\ORM\EntityRepository {
   public function findAll() {
       return $this->createQueryBuilder('a')
               ->orderBy('a.date','DESC')
               ->getQuery()
               ->getResult();
   }
}
