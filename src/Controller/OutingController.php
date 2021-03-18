<?php

namespace App\Controller;

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


        // On crée une instance de HttpClient pour pouvoir appeler des URL extérieures
        $client = HttpClient::create();

        // URL de notre API
        $url = "http://localhost:8888/projet_sortir/public/api/outings";

        // Réponse de l'API en méthode GET
        $response = $client->request('GET', $url);

        // Conversion de la réponse JSON en tableau PHP
        $outings = $response->toArray();

        return $this->render("default/home.html.twig", [
            'outings' => $outings,
            'currentUser' => $userId,
        ]);
    }
}
