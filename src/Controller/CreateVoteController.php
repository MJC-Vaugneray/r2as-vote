<?php

namespace App\Controller;

use App\Entity\Events;
use App\Entity\Proposal;
use App\Entity\Users;
use App\Entity\ResponseType1;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Routing\Annotation\Route;

class CreateVoteController extends AbstractController
{
    /**
     * @Route("/new-vote", name="create_vote")
     */
    public function index(Request $request, MailerInterface $mailer)
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

            $mail = $form->get('mail')->getData();

            $email = (new TemplatedEmail())
                ->from('vote@services.r2as.org')
                ->to($mail)
                ->subject('Admin : nouveau vote créé')
                ->htmlTemplate('emails/new_vote.html.twig')
                ->context([
                    'uuid' => $uuid,
                ]);

            $mailer->send($email);

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

    /**
     * @Route("/param-proposals/{uuid}", name="param_proposals")
     */
    public function paramproposals(Request $request, $uuid)
    {
        $event = $this->getDoctrine()
            ->getRepository(Events::class)
            ->findOneBy(['uuid' => $uuid]);
        $proposalss = $this->getDoctrine()
            ->getRepository(Proposal::class)
            ->findBy(['event_id' => $event]);
        $proposals = new Proposal();
        $proposals->setType("1");
        $proposals->setEventId($event);
        $form = $this->createFormBuilder($proposals)
            ->add('name')
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proposals = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proposals);
            $entityManager->flush();

            return $this->redirectToRoute('param_vote', ['uuid' => $uuid]);
        }

        return $this->render('create_vote/param_proposals.html.twig', [ 
            'uuid' => $uuid,
            'event' => $event,
            'proposalss' => $proposalss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/results/{uuid}", name="results")
     */
    public function results(Request $request, $uuid)
    {
        $event = $this->getDoctrine()
            ->getRepository(Events::class)
            ->findOneBy(['uuid' => $uuid]);
        $proposals = $this->getDoctrine()
            ->getRepository(Proposal::class)
            ->findBy(['event_id' => $event]);
        $responsesType1 = $this->getDoctrine()
            ->getRepository(ResponseType1::class)
            ->findBy(['event_id' => $event]);

        foreach ($responsesType1 as $r){
            $response[] = array(
                'proposal' => $r->getProposalId()->getId(),
                'positive' => $r->getpositive(),
                'negative' => $r->getnegative(),
                'abstention' => $r->getabstention(),
            );
        }

        return $this->render('create_vote/results.html.twig', [
            'uuid' => $uuid,
            'event' => $event,
            'proposals' => $proposals,
            'responsesType1' => $response,
        ]);
    }

    /**
     * @Route("/param-users/{uuid}", name="param_users")
     */
    public function paramusers(Request $request, $uuid, MailerInterface $mailer)
    {
        $event = $this->getDoctrine()
            ->getRepository(Events::class)
            ->findOneBy(['uuid' => $uuid]);
        $userss = $this->getDoctrine()
            ->getRepository(Users::class)
            ->findBy(['event_id' => $event]);
        $users = new Users();
        $uuidd = uuid_create(UUID_TYPE_RANDOM);
        $users->setUuid($uuidd);
        $users->setEventId($event);
        $form = $this->createFormBuilder($users)
            ->add('mail')
            ->add('name')
            ->add('factor', ChoiceType::class, [
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Valider'])
            ->getForm();
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $users = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($users);
            $entityManager->flush();

            $mail = $form->get('mail')->getData();

            $email = (new TemplatedEmail())
                ->from('vote@services.r2as.org')
                ->to($mail)
                ->subject('Votre invitation au vote')
                ->htmlTemplate('emails/new_user.html.twig')
                ->context([
                    'uuid' => $uuidd,
                    'event' => $event,
                ]);

            $mailer->send($email);


            return $this->redirectToRoute('param_users', ['uuid' => $uuid]);
        }
     return $this->render('create_vote/param_users.html.twig', [ 
            'uuid' => $uuid,
            'event' => $event,
            'userss' => $userss,
            'form' => $form->createView(),
        ]);
    }
}

