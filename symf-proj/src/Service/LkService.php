<?php


namespace App\Service;


use App\Repository\BeatRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class LkService
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;
    /**
     * @var BeatRepository
     */
    private BeatRepository $beatRepository;
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * LkService constructor.
     * @param UserRepository $userRepository
     * @param BeatRepository $beatRepository
     * @param TranslatorInterface $translator
     */
    public function __construct(UserRepository $userRepository, BeatRepository $beatRepository, TranslatorInterface $translator)
    {
        $this->userRepository = $userRepository;
        $this->beatRepository = $beatRepository;
        $this->translator = $translator;
    }

    /**
     * @param string $userName
     * @return array
     */
    public function profileInfo(string $userName) : array
    {
        try {
            $user = $this->userRepository->findUserByUsername($userName);
        } catch (NoResultException $e) {
            throw new NotFoundHttpException($this->translator->trans("exceptions.users_not_found"));
        } catch (NonUniqueResultException $e) {
            //Если вдруг найдется несколько одинаковых никнеймов, то берем первый найденный
            $user = reset($user);
        }

        $userBeats = $this->beatRepository->findBeatsByUser($user);

        return [$user, $userBeats];
    }
}