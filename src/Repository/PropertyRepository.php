<?php

namespace App\Repository;

use App\Entity\Property;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Entity\PropertySearch;

/**
 * @method Property|null find($id, $lockMode = null, $lockVersion = null)
 * @method Property|null findOneBy(array $criteria, array $orderBy = null)
 * @method Property[]    findAll()
 * @method Property[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /**
     * Get Query All Property
     */
    public function getAll()
    {
        $query = $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC');
        return $query->getQuery();
    }

    /**
     * Return Result Property Search
     */
    public function propertySearch($keyword, $status, $type, $bathrooms, $bedrooms, $garage, $minPrice)
    {
        $query =  $this->createQueryBuilder('p')
                        ->orderBy('p.id', 'DESC');

        if (!empty($keyword))
        {
            $query =  $query->andWhere('p.title LIKE :keyword');
            $query->setParameter('keyword', '%'. $keyword .'%');
        }

        if ($type) {
            $query = $query->andWhere('p.types = :type');
            $query->setParameter('type', $type);
        }

        if ($status != 0) {
            $query = $query->andWhere('p.status = :status');
            $query->setParameter('status', $status);
        }

        if ($bedrooms != 0) {
            $query = $query->andWhere('p.bedrooms = :bedrooms');
            $query->setParameter('bedrooms', $bedrooms);
        }

        if ($garage != 0) {
            $query = $query->andWhere('p.garage = :garage');
            $query->setParameter('garage', $garage);
        }

        if ($bathrooms != 0) {
            $query = $query->andWhere('p.bathrooms = :bathrooms');
            $query->setParameter('bathrooms', $bathrooms);
        }

        if ($minPrice != 0) {
            $query = $query->andWhere('p.price <= :price');
            $query->setParameter('price', $minPrice);
        }

        return $query->getQuery()->getResult();

    }

    public function getRandomProperty()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('RAND()')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }



    // /**
    //  * @return Property[] Returns an array of Property objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Property
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
