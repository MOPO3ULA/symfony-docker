<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class UserCompetition
{
    /**
     * @var User|null $user
     */
    private ?User $user = null;

    /**
     * @var UserRepository $userRepository
     */
    private UserRepository $userRepository;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        /**
         * @var User|null $user
         */
        $user = $security->getUser();

        if ($user) {
            $this->user = $user;
        }
    }

    public function getUserCompetitionIds(): array
    {
        $userCompetitionIds = [];

        if ($this->user) {
            $userCompetitions = $this->userRepository->findUserCompetitions($this->user);

            foreach ($userCompetitions as $userCompetition) {
                foreach ($userCompetition as $competitionId) {
                    $userCompetitionIds[] = $competitionId;
                }
            }
        }

        return $userCompetitionIds;
    }

    public function getIsUserCompetitionFound($competitionId): bool
    {
        $isCompetitionFound = false;

        if ($this->user) {
            $competitionFound = $this->userRepository->findUserCompetitionById($this->user, $competitionId);

            if ($competitionFound) {
                $isCompetitionFound = true;
            }
        }

        return $isCompetitionFound;
    }
}