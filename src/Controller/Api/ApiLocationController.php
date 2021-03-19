<?php


namespace App\Controller\Api;


use App\Repository\CityRepository;
use App\Repository\LocationRepository;
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
class ApiLocationController extends AbstractController
{
    /**
     * @Route("/location/city/{idCity}", name="api_locations_city", requirements={"idCity": "\d+"}, methods={"GET"})
     * @param LocationRepository $locationRepo
     * @param CityRepository $cityRepo
     * @param SerializerInterface $serializer
     * @param $idCity
     * @return JsonResponse
     */
    public function listOfLocation(LocationRepository $locationRepo, CityRepository $cityRepo, SerializerInterface $serializer,
                                   $idCity)
    {
        $idCity = (int)$idCity;
        $locations = $locationRepo->findByCity($idCity);

        // Sérialiser la collection d'objets Outings
        $json = $serializer->serialize($locations, 'json', [
            'groups' => 'location'
        ]);
        // On retourne la réponse au format JSON

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
}