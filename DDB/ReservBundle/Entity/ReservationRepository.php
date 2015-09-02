<?php

namespace DDB\ReservBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReservationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReservationRepository extends EntityRepository
{
    public function findReservations($nb) {
        return $this->createQueryBuilder('a')
                ->orderBy('a.date','DESC')
                ->setMaxResults($nb)
                ->getQuery()
                ->getResult()
                ;
    }
   
    public function isAlreadyReserved_(Reservation $reservation) {
        return $this->createQueryBuilder('a')
                ->where('a.bungalow=:bungalow')
                ->andWhere('a.debut>:debut')
                ->andWhere('a.fin<:fin')
                ->setParameter('bungalow', $reservation->getBungalow())
                ->setParameter('debut', $reservation->getDebut())
                ->setParameter('fin', $reservation->getFin())
                ->getQuery()
                ->getResult();
    }
    
    public function isAlreadyReserved(Reservation $reservation) {
        
        return $this->createQueryBuilder('a')
                ->where('a.bungalow=:bungalow')
                ->andWhere('a.fin>:today')
                ->andWhere (':debut BETWEEN a.debut AND a.fin OR :fin BETWEEN a.debut AND a.fin')
                ->setParameter('bungalow', $reservation->getBungalow())
                ->setParameter('debut', $reservation->getDebut())
                ->setParameter('fin', $reservation->getFin())
                ->setParameter('today', new \DateTime)
                ->getQuery()
                ->getResult();
    }
    
    public function findByBungalow($id) {
        $now = new \DateTime();
        return $this->createQueryBuilder('a')
                ->where('a.bungalow=:id')
                ->andWhere('a.fin>:now')
                ->orderBy('a.debut','ASC')
                ->setParameter('id', $id)
                ->setParameter('now', $now)
                ->getQuery()
                ->getResult();
        
    }
    
    public function findReservationsByMonth($nb) {
        $maintenant = new \DateTime;
        $avant = new \DateTime;
        $avant->modify("$nb months ago");
        
        return  $this->createQueryBuilder('a')
                ->where('a.date between :avant AND :maintenant')
                ->setParameter('maintenant', $maintenant)
                ->setParameter('avant',$avant)
                ->orderBy('a.date','DESC')
                ->getQuery()
                ->getResult();
    }
    
    public function findAllReservations() {
        return $this->createQueryBuilder('a')
                ->orderBy('a.date','ASC')
                ->getQuery()
                ->getResult()
                ;
    }

}
