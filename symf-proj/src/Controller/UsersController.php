<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Entity\User;
use App\Repository\BeatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users/{username}", name="detailUser")
     * @param Request $request
     * @return Response
     */
    public function detailUser(Request $request): Response
    {
        $username = $request->get('username');

        /*** @var $userRepository UserRepository*/
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        try {
            $user = $userRepository->findUserByUsername($username);
        } catch (NoResultException $e) {
            throw new NotFoundHttpException('Такого пользователя не существует');
        } catch (NonUniqueResultException $e) {
            //Если вдруг найдется несколько одинаковых никнеймов, то берем первый найденный
            $user = reset($user);
        }

        /*** @var $beatRepository BeatRepository */
        $beatRepository = $this->getDoctrine()->getRepository(Beat::class);

        $userBeats = $beatRepository->findBeatsByUser($user);

        return $this->render('@TwigTemplate/users/detail.html.twig', [
            'beats' => $userBeats,
            'user' => $user
        ]);
    }
}
