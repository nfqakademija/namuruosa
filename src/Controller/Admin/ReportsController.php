<?php


namespace App\Controller\Admin;


use App\Entity\Reports;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReportsController extends AbstractController
{
    public function getAllReports()
    {
        $reports = $this->getDoctrine()
            ->getRepository(Reports::class)
            ->getAllReports();

        return $this->render('admin/reports.html.twig', [
            'reports' => $reports,
        ]);
    }
}