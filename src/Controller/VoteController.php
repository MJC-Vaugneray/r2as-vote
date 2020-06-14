<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Events;
use App\Entity\Proposal;
use App\Entity\Users;
use App\Entity\ResponseType1;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends AbstractController
{
    /**
     * @Route("/vote/{uuid}", name="vote")
     */
    public function index(Request $request, $uuid)
    {
        $user = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findOneBy(['uuid' => $uuid]);

        $factor = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findOneBy(['uuid' => $uuid])
            ->getFactor();

        $event = $user -> getEventId();
        $proposals = $event -> getProposals();
        $responsesType1 = $this->getDoctrine()
            ->getRepository(ResponseType1::class)
            ->findBy(['user_id' => $user]);

        $eventstate = $event->getState();
        if ($eventstate == false) {
            return $this->render('vote/deactivated_vote.html.twig', [
                'user' => $user,
                'uuid' => $uuid,
                'event' => $event,
            ]);    
        }
        $exist[] = '0';
        foreach ($responsesType1 as $response){
             $exist[] = ($response->getProposalId()->getId());
        }

        return $this->render('vote/index.html.twig', [
            'user' => $user,
            'uuid' => $uuid,
            'event' => $event,
            'factor' => $factor,
            'exist' => $exist,
            'proposals' => $proposals,
            'responsesType1' => $responsesType1,
        ]);
    }

    /**
     * @Route("/vote/{uuid}/1/{proposalid}/{status}", name="submitvotetype1")
     */
    public function submitvotetype1(Request $request, $uuid, $proposalid, $status)
    {
        $user = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findOneBy(['uuid' => $uuid]);

        $proposal = $this->getDoctrine()
            ->getRepository(Proposal::class)
            ->findOneBy(['id' => $proposalid]);

        $factor = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findOneBy(['uuid' => $uuid])
            ->getFactor();

        $event = $user -> getEventId();
        $entityManager = $this->getDoctrine()->getManager();
        $vote = new ResponseType1();
        $vote->setEventId($event);
        $vote->setUserId($user);
        $vote->setProposalId($proposal);

        if ($status == 'positive') {
            $vote->setPositive($factor);
        }

        if ($status == 'negative') {
            $vote->setNegative($factor);
        }

        if ($status == 'abstention') {
            $vote->setAbstention($factor);
        }

        $vote_exist = $entityManager->getRepository(ResponseType1::class)->findBy(['user_id' => $user, 'proposal_id' => $proposal]);
        if ($vote_exist) {
            return $this->redirectToRoute('vote', ['uuid' => $uuid]);;
        }else {
            $entityManager->persist($vote);
            $entityManager->flush();
        }


            return $this->redirectToRoute('vote', ['uuid' => $uuid, 'factor' => $factor]);
        
    }
}
