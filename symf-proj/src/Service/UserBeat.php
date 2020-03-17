<?php

namespace App\Service;

use App\Entity\Beat;
use App\Entity\User;
use App\Repository\CompetitionRepository;
use App\Repository\UserRepository;
use App\Validate\FileValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Competition;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Throwable;

class UserBeat
{
    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * @var CompetitionRepository $competitionRepository
     */
    private CompetitionRepository $competitionRepository;

    /**
     * @var ParameterBagInterface $parameterBag
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var Security $security
     */
    private Security $security;

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    private ?array $errors = null;

    /**
     * @var Beat $beat
     */
    private Beat $beat;

    public function __construct(ManagerRegistry $managerRegistry,
                                EntityManagerInterface $em, ParameterBagInterface $parameterBag,
                                Security $security, LoggerInterface $logger, UserRepository $userRepository)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
        $this->security = $security;
        $this->logger = $logger;

        /**
         * @var CompetitionRepository $competitionRepository
         */
        $competitionRepository = $managerRegistry->getRepository(Competition::class);
        $this->competitionRepository = $competitionRepository;
        $this->beat = new Beat();

        /**
         * @var User|null $user
         */
        $user = $security->getUser();

        if ($user) {
            $userId = $user->getId();
            $userObj = $userRepository->find($userId);

            $this->beat->setUser($userObj);
        } else {
            $this->errors[] = 'Anonymous user';
        }
    }

    /**
     * @param Request $request
     * @return bool|string
     */
    public function saveUserBeat(Request $request)
    {
        if ($this->errors) {
            $this->logger->error($this->errors[0], ['class' => static::class]);
            return false;
        }

        /**
         * @var UploadedFile $file
         */
        $file = $request->files->get('file');

        /**
         * @var UploadedFile $picture
         */
        $picture = $request->files->get('picture');

        $validationResult = FileValidator::validateMp3($file);

        if (is_string($validationResult)) {
            $this->logger->error($validationResult, ['class' => static::class]);
            return $validationResult;
        }

        $saveDestination = $this->parameterBag->get('kernel.project_dir') . '/public/upload/beats/';
        $saveDestinationPicture = $this->parameterBag->get('kernel.project_dir') . '/public/upload/images/beats';

        $fullPath = $this->saveUploadedFile($saveDestination, $file);
        $this->saveUploadedFile($saveDestinationPicture, $picture);

        $mp3 = new Mp3Info($fullPath);
        $mp3Length = $mp3->getDurationFormatted();

        $this->beat->setTitle($request->request->get('title'));
        $this->beat->setDescription($request->request->get('description'));
        $this->beat->setBeatLength($mp3Length);
        $this->beat->setFileUrl('/upload/samples/' . $file->getClientOriginalName());
        $this->beat->setPicture('/upload/images/beats/' . $picture->getClientOriginalName());

        $postLink = $request->request->get('postLink');

        try {
            /**
             * @var Competition $competition
             */
            $competition = $this->competitionRepository->findCompetitionByPostLink($postLink);

            $sample = $competition->getSample();

            $category = $sample->getCategory();
            $genre = $sample->getGenre();

            $this->beat->setCategory($category);
            $this->beat->setGenre($genre);
        } catch (NonUniqueResultException $e) {
            $this->logger->error($e->getMessage(), [$e->getTraceAsString()]);
            return false;
        }

        $this->beat->setCompetition($competition);

        try {
            $this->em->persist($this->beat);
            $this->em->flush();
        } catch (Throwable $exception) {
            $this->logger->error('Ошибка записи Beat в базу', ['class' => static::class]);
            return false;
        }

        return true;
    }

    /**
     * @param string $saveDestination
     * @param UploadedFile $file
     * @param string $filename
     * @return string
     */
    public function saveUploadedFile(string $saveDestination, UploadedFile $file, string $filename = ''): string
    {
        if ($filename) {
            $originalFilename = $filename;
        } else {
            $originalFilename = $file->getClientOriginalName();
        }

        $file->move($saveDestination, $originalFilename);
        return $saveDestination . $originalFilename;
    }
}