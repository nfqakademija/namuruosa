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

    /**
     * @param $id
     * @return mixed
     */
    public function loadByUser($id)
    {
        return $this->em->getRepository('App:Job')->findByUserId($id);
    }


}