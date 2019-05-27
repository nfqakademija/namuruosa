<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getAllUsers()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT 
                    u.id, u.username, u.email, u.last_login, u.roles, u.first_name, u.last_name,
                    COUNT(DISTINCT j.id) AS job_count,
                    COUNT(DISTINCT s.id) AS service_count,
                    COUNT(DISTINCT am.id) AS accepted_match_count,
                    COUNT(DISTINCT nm.id) AS not_accepted_match_count,
                    COUNT(DISTINCT rm.id) AS rejected_match_count
                FROM fos_user u
                    LEFT JOIN job j
                        ON u.id = j.user_id
                    LEFT JOIN service s
                        ON u.id = s.user_id
                    LEFT JOIN matches am
                        ON u.id = am.caller_id
                        AND am.accepted_at IS NOT NULL  
                    LEFT JOIN matches nm
                        ON u.id = nm.caller_id
                        AND nm.accepted_at IS NULL    
                    LEFT JOIN matches rm
                        ON u.id = rm.caller_id
                        AND rm.rejected_at IS NOT NULL       
                GROUP BY u.id           
                ';

        $query = $conn->prepare($sql);
        $query->execute();
        return $query->fetchAll();
    }
}
