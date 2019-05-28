<?php

namespace App\Repository;

use App\Entity\Reports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reports|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reports|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reports[]    findAll()
 * @method Reports[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reports::class);
    }

    public function getAllReports()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT 
                    r.id, r.reporter_user_id, r.reported_user_id, r.report, r.created_at,
                    x.username as reporter_username,
                    y.username as reported_username   
                FROM 
                    reports r 
                    LEFT JOIN fos_user x 
                        ON x.id = r.reporter_user_id
                    LEFT JOIN fos_user y 
                        ON y.id = r.reported_user_id
                GROUP BY r.id
                ';

        $query = $conn->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
