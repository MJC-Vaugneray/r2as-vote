<?php

namespace App\Controller;

use App\Entity\Events;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CreateVoteController extends AbstractController
{
    /**
     * @Route("/new-vote", name="create_vote")
     */
    public function index(Request $request)
    {
        $uuid = uuid_create(UUID_TYPE_RANDOM);
        $event = new Events();
        $event->setUuid($uuid);
        $event->setState(true);
        $form = $this->createFormBuilder($event)
            ->add('name')
            ->add('description')
            ->add('mail')
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('param_vote', ['uuid' => $uuid]);
        }


        return $this->render('create_vote/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/param-vote/{uuid}", name="param_vote")
     */
    public function paramvote(Request $request, $uuid)
    {
        $event = $this->getDoctrine()
            ->getRepository(Events::class)
            ->findOneBy(['uuid' => $uuid]);


        return $this->render('create_vote/param_vote.html.twig', [ 
            'uuid' => $uuid,
            'event' => $event,
        ]);
    }
}
