<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BeatsController extends AbstractController
{
    /**
     * @Route("/beats", name="beatsList")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $beatsList = [];
        for ($i = 0; $i < 10; $i++) {
            $beatsList[] = rand(1, 100);
        }

        return $this->render('@TwigTemplate/beats/index.html.twig', [
            'beats' => $beatsList
        ]);
    }

    /**
     * @Route("/beats/{id}", name="beatsDetail", requirements={"id": "[0-9]+"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Request $request)
    {
        return $this->render('@TwigTemplate/beats/detail.html.twig', [
            'id' => $request->get('id')
        ]);
    }
}
