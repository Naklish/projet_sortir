<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Form\OutingFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
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
     * @Route("/outing/add", name="new_outing")
     */
    public function add(EntityManagerInterface $em)
    {
        $outing = new Outing();
        $outingForm = $this->createForm(OutingFormType::class, $outing);

        return $this->render('outing/add.html.twig', [
            "outingForm" => $outingForm->createView()
        ]);
    }
}
