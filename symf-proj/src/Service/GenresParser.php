<?php

namespace App\Service;


use App\Entity\Genre;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class GenresParser
{
    private const link = 'https://www.looperman.com/loops/genres';

    /**
     * @var Crawler $crawler
     */
    private Crawler $crawler;

    /**
     * @var Client $client
     */
    private Client $client;

    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->client = new Client(['base_uri' => self::link]);
        $this->em = $em;
    }

    public function run()
    {
        $genres = $this->getAllGenres();

        foreach ($genres as $genre) {
            $objGenre = new Genre();
            $objGenre->setName($genre);

            $this->em->persist($objGenre);
        }

        $this->em->flush();
    }

    public function getAllGenres(): array
    {
        $genres = [];
        $htmlContent = $this->client->request('GET')->getBody()->getContents();

        $this->crawler = new Crawler($htmlContent);
        $catClasses = $this->crawler->filter('.icon-tag');

        /**
         * @var \DOMElement $cat
         */
        foreach ($catClasses as $cat) {
            $genres[] = $cat->textContent;
        }

        return $genres;
    }
}