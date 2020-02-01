<?php

namespace App\Model;

use App\Entity\Competition;
use App\Parse\CompetitionParser;
use App\Validate\CompetitionValidator;
use GuzzleHttp\Client;

class CompetitionModel
{
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

    private $isRandomFound = false;

    /**
     * @var $client Client
     */
    private $client;

    /**
     * @var $crawler CompetitionParser
     */
    private $crawler;

    public function getRandomBeat(): Competition
    {
        $this->setClient(new Client(['base_uri' => self::link]));

        $htmlContent = $this->getPage('loops', ['query' => self::arRequestQueryParams]);
        $this->setCrawler($this->crawler = new CompetitionParser($htmlContent));

        $countSample = $this->getCountOfSamples();

        if ($countSample) {
            while (!$this->isRandomFound) {
                $countPages = ceil($countSample / self::countOfBeatsOnPage);

                try {
                    $randomPage = random_int(1, (int)$countPages);
                    $randomSample = random_int(1, self::countOfBeatsOnPage);
                } catch (\Exception $e) {
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
            $this->setCrawler(new CompetitionParser($htmlDetailBeat));

            $competition = new Competition();
            $beatParameters = $this->crawler->getBeatParameters();
            $competition = $this->addBeatParameters($competition, $beatParameters);

            return $competition;
        }

        return $this->getRandomBeat();
    }

    public function addBeatParameters(Competition $competition, array $beatParameters): Competition
    {
        foreach ($beatParameters as $parameter => $parameterValue) {
            if (is_array($parameterValue)) {
                foreach ($parameterValue as $param => $paramValue) {
                    $methodName = 'set' . lcfirst($parameter . $param);
                    $competition->$methodName($paramValue);
                }
            } else if ($parameter !== 'genre' && $parameter !== 'category') {
                $methodName = 'set' . lcfirst($parameter);
                $competition->$methodName($parameterValue);
            }
        }

        return $competition;
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
        $this->crawler = new CompetitionParser($pagesContent);

        $samplesOnPage = $this->crawler->getObjectsOfSamplesOnPage();

        $beatLink = null;

        /**
         * @var $sample \DOMElement
         */
        foreach ($samplesOnPage as $key => $sample) {
            if ($key === $sampleNumber) {
                $beatLink = $this->crawler->getHrefString($sample);
            }
        }

        return $beatLink;
    }

    /**
     * @param null $uri
     * @param array $queryParams
     * @return string
     */
    public function getPage($uri = null, $queryParams = []): string
    {
        $response = $this->client->request('GET', $uri, $queryParams);

        return $response->getBody()->getContents();
    }
}