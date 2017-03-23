<?php

namespace Ecommerce\FrontBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
    public function recherche($name)
    {
        $query = $this->createQueryBuilder('p');
        $nameLike = $query->expr()->like('p.name', ':name');
        $query->where($nameLike)
            ->andWhere('p.available = 1')
            ->orderBy('p.name')
            ->setParameter('name', sprintf('%%%s%%', $name))
        ;

        return $query->getQuery()->getResult();
    }

    public function findProduitsInArray($array)
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.id IN (:array)')
            ->setParameter('array', $array);

        return $qb->getQuery()->getResult();
    }

    public function getAllWithIndex(){
        $qb = $this->createQueryBuilder('p');

        return $qb->getQuery()->getResult();
    }
}
