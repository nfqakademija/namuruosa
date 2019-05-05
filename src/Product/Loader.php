<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.5
 * Time: 08.53
 */

namespace App\Product;


use Doctrine\ORM\EntityManagerInterface;

class Loader
{

    private $em;

    /**
     * Loader constructor.
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function loadAll($id)
    {
        return $this->em->getRepository('App:Service')->findServicesByUserId($id);
    }


}