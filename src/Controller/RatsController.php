<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use JMS\Serializer\SerializationContext;

use App\Repository\RatRepository;
use App\Entity\Rat;


class RatsController extends Controller
{
/**
     * @Route("/rats", name="rats")
     * @param RatRepository $ratRepository
     * @return JsonResponse
     */
	public function index(SerializerInterface $serializer)
    {
        $data = $this->getDoctrine()
            ->getRepository(Rat::class)
            ->findAll();

		$result = $serializer->serialize($data, 'json', [
			'circular_reference_handler' => function ($object) { return $object; },
			'groups' => ['rat']
		]);

		return new JsonResponse($result);

    }	



/**
     * Gets the rat by a given id.
     *
     * @param string $id
     *
     * @return View
     */
    public function getRatAction($id)
    {
		$rat = $this->getDoctrine()
			->getRepository(Rat::class)
			->find(Rat::class, $id);

        if (null === $rat) {
            throw new NotFoundHttpException(sprintf("Rat with id '%s' could not be found.", $id));
        }

        $view = View::create()
            ->setData(['rat' => $rat]);

        return $this->getViewHandler()->handle($view);
    }

}
