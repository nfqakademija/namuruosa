<?php


namespace App\Controller\Admin;


use App\Entity\Match;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MatchesController extends AbstractController
{
    public function getAllMatches()
    {
        $matches = $this->getDoctrine()
            ->getRepository(Match::class)
            ->getAllMatches();
        dump($matches);
        return $this->render('admin/matches.html.twig', [
            'matches' => $matches,
        ]);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function adminDeleteMatch($id)
    {
        $em = $this->getDoctrine()->getManager();
        $match = $em
            ->getRepository(Match::class)
            ->find($id);

        $em->remove($match);
        $em->flush();

        return $this->redirectToRoute('admin_matches');
    }
}