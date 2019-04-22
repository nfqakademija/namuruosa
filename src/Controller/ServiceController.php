<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.4.22
 * Time: 11.31
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServiceController
 * @package App\Controller
 *
 */
class ServiceController extends AbstractController
{

    /**
     * @Route("/service/add", name="serviceAdd")
     */
    public function add()
    {
        return $this->render("service/add.html.twig", [
            'posts' => 'This is a post',
        ]);
    }
}