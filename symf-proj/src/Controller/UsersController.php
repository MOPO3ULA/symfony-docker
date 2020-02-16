<?php

namespace App\Controller;


use App\Service\LkService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class UsersController extends AbstractController
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @var ManagerRegistry $managerRegistry
     */
    private ManagerRegistry $managerRegistry;
    /**
     * @var LkService
     */
    private LkService $lkService;

    /**
     * UsersController constructor.
     * @param TranslatorInterface $translator
     * @param ManagerRegistry $managerRegistry
     * @param LkService $lkService
     */
    public function __construct(
        TranslatorInterface $translator,
        ManagerRegistry $managerRegistry,
        LkService $lkService)
    {
        $this->translator = $translator;
        $this->managerRegistry = $managerRegistry;
        $this->lkService = $lkService;
    }

    /**
     * @Route("/profile/{username}", name="detailUser")
     * @param Request $request
     * @return Response
     */
    public function detailUser(Request $request): Response
    {
        $username = $request->get('username');

        [$user, $userBeats] = $this->lkService->profileInfo($username);

        return $this->render('@TwigTemplate/users/detail.html.twig', [
            'beats' => $userBeats,
            'user' => $user
        ]);
    }
}
