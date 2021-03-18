<?php


namespace App\Controller\Api;


use App\Repository\OutingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiOutingController
 * @package App\Controller\Api
 * @Route("/api")
 */
class ApiOutingController extends AbstractController
{
    /**
     * @Route("/outings", name="api_outing_list", methods={"GET"})
     */
    public function outingList(OutingRepository $outingRepository, SerializerInterface $serializer) {
        $outings = $outingRepository->findAll();

        // Sérialiser la collection d'objets Outings
        $json = $serializer->serialize($outings, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        // On retourne la réponse au format JSON
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}