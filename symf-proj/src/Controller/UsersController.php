<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Entity\User;
use App\Repository\BeatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    public function __construct(TranslatorInterface $translator, ManagerRegistry $managerRegistry)
    {
        $this->translator = $translator;
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * @Route("/users/{username}", name="detailUser")
     * @param Request $request
     * @return Response
     */
    public function detailUser(Request $request): Response
    {
        $username = $request->get('username');

        /**
         * @var UserRepository $userRepository
         */
        $userRepository = $this->managerRegistry->getRepository(User::class);
        $user = null;

        try {
            $user = $userRepository->findUserByUsername($username);
        } catch (NoResultException $e) {
            throw new NotFoundHttpException($this->translator->trans("exceptions.users_not_found"));
        } catch (NonUniqueResultException $e) {
            //Если вдруг найдется несколько одинаковых никнеймов, то берем первый найденный
            $user = reset($user);
        }

        /**
         * @var BeatRepository $beatRepository
         */
        $beatRepository = $this->managerRegistry->getRepository(Beat::class);

        $userBeats = $beatRepository->findBeatsByUser($user);

        return $this->render('@TwigTemplate/users/detail.html.twig', [
            'beats' => $userBeats,
            'user' => $user
        ]);
    }
}
