<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function getAllCategories()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT 
                    c.id, 
                    c.name, 
                    COUNT(DISTINCT s.service_id) as service_count, 
                    COUNT(DISTINCT j.job_id) AS job_count
                FROM 
                    category c
                    LEFT JOIN service_category s 
                        ON c.id = s.category_id
                    LEFT JOIN job_category j 
                        ON c.id = j.category_id
                GROUP BY c.id
                ';

        $query = $conn->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
