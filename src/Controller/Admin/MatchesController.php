<?php


namespace App\Controller\Admin;


use App\Entity\Match;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MatchesController extends AbstractController
{
    public function getAllMatches()
    {
        $matches = $this->getDoctrine()
            ->getRepository(Match::class)
            ->getAllMatches();

        return $this->render('admin/matches.html.twig', [
            'matches' => $matches,
        ]);
    }
}