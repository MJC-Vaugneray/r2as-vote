<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreateVoteController extends AbstractController
{
    /**
     * @Route("/create/vote", name="create_vote")
     */
    public function index()
    {
        return $this->render('create_vote/index.html.twig', [
            'controller_name' => 'CreateVoteController',
        ]);
    }
}
