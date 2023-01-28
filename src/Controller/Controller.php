<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

use FOS\RestBundle\Controller\AbstractFOSRestController;


class Controller extends AbstractFOSRestController
{

	private $entityManager;

	public function __construct(EntityManagerInterface $em) {

		$this->entityManager = $em;
        
	}

	public function getDoctrine(){

		return $this->entityManager;
	}

}
