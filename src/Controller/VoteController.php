<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    /**
     * @Route("/vote", name="vote")
     */
    public function index()
    {
        return $this->render('vote/index.html.twig', [
            'controller_name' => 'VoteController',
        ]);
    }
}
