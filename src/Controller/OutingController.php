<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\State;
use App\Form\OutingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OutingController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function list(): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_REMEMBERED');
        $currentUser = $this->getUser();
        $userId = $currentUser->getId();

        $outingRepo = $this->getDoctrine()->getRepository(Outing::class);
        $outings = $outingRepo->findAll();


        return $this->render("default/home.html.twig", [
            'outings' => $outings,
            'currentUser' => $userId,
        ]);
    }

    //Fonction qui ajoute une nouvelle sortie
    /**
     * @Route("/outing/add", name="new_outing", methods={"GET"})
     */
    public function add(EntityManagerInterface $em)
    {
        $outing = new Outing();
        $outingForm = $this->createForm(OutingFormType::class, $outing);

        return $this->render('outing/add.html.twig', [
            "outingForm" => $outingForm->createView()
        ]);
    }

    /**
     * @Route("/outing/add", name="new_outing_create", methods={"POST"})
     */
    public function createOuting(Request $request, EntityManagerInterface $em)
    {
        $stateRepo = $this->getDoctrine()->getRepository(State::class);

        $user = $this->getUser();
        $outingRepo = $this->getDoctrine()->getRepository(Outing::class);
        $outing = new Outing();

        $outingForm = $this->createForm(OutingFormType::class, $outing);
        $outingForm->handleRequest($request);

        if($outingForm->isValid() && $outingForm->isSubmitted()){
            if($outingForm->get('create')->isClicked()){
                $state = $stateRepo->find(1);
                $outing->setOUsers($user);
                $outing->setState($state);
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', 'La sortie a été créée.');
            } elseif($outingForm->get('publish')->isClicked()){
                $state = $stateRepo->find(2);
                $outing->setOUsers($user);
                $outing->setState($state);
                $em->persist($outing);
                $em->flush();
                $this->addFlash('success', 'La sortie a été publiée');
            }
        }
        return $this->redirectToRoute('new_outing');
    }
}
