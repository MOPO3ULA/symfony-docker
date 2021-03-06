<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Genre;
use App\Entity\Sample;
use App\Parse\CompetitionParser;
use App\Repository\CategoryRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Competition;
use App\Validate\CompetitionValidator;
use Exception;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Throwable;

class CompetitionGenerator
{
    /**
     * @var ManagerRegistry $managerRegistry
     */
    private ManagerRegistry $managerRegistry;

    /**
     * @var CompetitionParser $crawler
     */
    private CompetitionParser $crawler;

    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * @var Client $client
     */
    private Client $client;

    /**
     * @var CategoryRepository $categoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * @var GenreRepository $genreRepository
     */
    private GenreRepository $genreRepository;

    /**
     * @var File $file
     */
    private File $file;

    /**
     * @var ParameterBagInterface $parameterBag
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    private bool $isRandomFound = false;

    public const parameterWhen = [
        'day' => '1',
        'week' => '2',
        'month' => '3',
        'two_months' => '4'
    ];
    public const arRequestQueryParams = [
        'order' => 'date',
        'dir' => 'd',
        'when' => self::parameterWhen['day']
    ];
    public const countOfBeatsOnPage = 25;
    public const link = 'https://www.looperman.com';

    public function __construct(ManagerRegistry $managerRegistry, CompetitionParser $crawler,
                                EntityManagerInterface $em, File $file, ParameterBagInterface $parameterBag,
                                LoggerInterface $logger)
    {
        $this->managerRegistry = $managerRegistry;
        $this->crawler = $crawler;
        $this->em = $em;
        $this->file = $file;
        $this->parameterBag = $parameterBag;
        $this->logger = $logger;
    }

    public function createCompetition()
    {
        /**
         * @var CategoryRepository $categoryRepository
         */
        $categoryRepository = $this->managerRegistry->getRepository(Category::class);

        /**
         * @var GenreRepository $genreRepository
         */
        $genreRepository = $this->managerRegistry->getRepository(Genre::class);

        $this->categoryRepository = $categoryRepository;
        $this->genreRepository = $genreRepository;

        $sample = $this->getRandomSample();

        $homeDir = $this->parameterBag->get('kernel.project_dir');
        $savedFilePath = $this->saveSample($sample->getFile(), $homeDir . '/public/upload/samples/');

        $sample->setFile($savedFilePath);

        try {
            $this->em->persist($sample);
            $this->em->flush();
        } catch (Throwable $exception) {
            return 'An unexpected error has occurred';
        }

        $competition = new Competition();
        $competition->setSample($sample);
        $competition->setLoopermanLink($this->getLoopermanSampleLink());

        try {
            $this->em->persist($competition);
            $this->em->flush();
        } catch (Throwable $exception) {
            return 'An unexpected error has occurred';
        }

        return true;
    }

    private function getLoopermanSampleLink(): string
    {
        return $this->crawler->getLoopermanLink();
    }

    public function getRandomSample(): Sample
    {
        $this->setClient(new Client(['base_uri' => self::link]));

        $htmlContent = $this->getPage('loops', ['query' => self::arRequestQueryParams]);
        $this->crawler = $this->crawler->setCrawler($htmlContent);

        $countSample = $this->getCountOfSamples();
        $beatLink = '';

        if ($countSample) {
            while (!$this->isRandomFound) {
                $countPages = ceil($countSample / self::countOfBeatsOnPage);

                try {
                    $randomPage = random_int(1, (int)$countPages);
                    $randomSample = random_int(1, self::countOfBeatsOnPage);
                } catch (Exception $e) {
                    die('An unexpected error has occurred');
                }

                $requestParams = array_merge(['page' => (string)$randomPage], self::arRequestQueryParams);
                $htmlPages = $this->getPage('loops', ['query' => $requestParams]);

                $beatLink = $this->getSampleLink($htmlPages, $randomSample);

                if ($beatLink) {
                    $this->isRandomFound = true;
                }
            }

            $this->setClient(new Client(['base_uri' => $beatLink]));
            $htmlDetailBeat = $this->getPage();
            $this->crawler = $this->crawler->setCrawler($htmlDetailBeat);

            $sample = new Sample();
            $sampleParameters = $this->crawler->getSampleParameters();
            $sample = $this->addBeatParameters($sample, $sampleParameters);

            return $sample;
        }

        return $this->getRandomSample();
    }

    public function addBeatParameters(Sample $sample, array $beatParameters): Sample
    {
        foreach ($beatParameters as $parameter => $parameterValue) {
            if (is_array($parameterValue)) {
                foreach ($parameterValue as $param => $paramValue) {
                    $methodName = 'set' . lcfirst($parameter . $param);
                    $sample->$methodName($paramValue);
                }
            } else {
                if ($parameter !== 'genre' && $parameter !== 'category') {
                    $methodName = 'set' . lcfirst($parameter);
                    $sample->$methodName($parameterValue);
                } else {
                    if ($parameter === 'category') {
                        try {
                            $category = $this->categoryRepository->findCategoryByName($parameterValue);
                            $sample->setCategory($category);
                        } catch (NonUniqueResultException $exception) {
                            continue;
                        }
                    } elseif ($parameter === 'genre') {
                        try {
                            $genre = $this->genreRepository->findGenreByName($parameterValue);
                            $sample->setGenre($genre);
                        } catch (NonUniqueResultException $exception) {
                            continue;
                        }

                    }
                }
            }
        }

        return $sample;
    }

    /**
     * @return CompetitionParser
     */
    public function getCrawler(): CompetitionParser
    {
        return $this->crawler;
    }

    /**
     * @param CompetitionParser $crawler
     */
    public function setCrawler(CompetitionParser $crawler): void
    {
        $this->crawler = $crawler;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     */
    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * Получаем общее количество страниц
     * @return int
     */
    private function getCountOfSamples(): int
    {
        $countOfSamplesString = $this->crawler->getHtmlCountOfSamples();
        $countOfSamples = CompetitionValidator::getCountOfSamplesFromString($countOfSamplesString);

        return $countOfSamples;
    }

    /**
     * @param string $pagesContent
     * @param int $sampleNumber
     * @return string
     */
    private function getSampleLink(string $pagesContent, int $sampleNumber): ?string
    {
        $this->crawler = $this->crawler->setCrawler($pagesContent);
        $samplesOnPage = $this->crawler->getObjectsOfSamplesOnPage();

        $beatLink = null;

        /**
         * @var \DOMElement $sample
         */
        foreach ($samplesOnPage as $key => $sample) {
            if ($key === $sampleNumber) {
                $beatLink = $this->crawler->getHrefString($sample);
            }
        }

        return $beatLink;
    }

    /**
     * @param string|null $uri
     * @param array $queryParams
     * @return string
     */
    public function getPage($uri = null, $queryParams = []): string
    {
        $response = $this->client->request('GET', $uri, $queryParams);

        return $response->getBody()->getContents();
    }

    /**
     * @param string|null $download
     * @param string $pathToSave
     * @param string $filename
     * @return string
     */
    private function saveSample(?string $download, string $pathToSave, string $filename = ''): string
    {
        return $this->file->saveFile($download, $pathToSave, $filename);
    }
}