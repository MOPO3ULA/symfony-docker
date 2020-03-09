<?php

namespace App\Service;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class CategoriesParser
{
    private const link = 'https://www.looperman.com/loops/cats';

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
        $categories = $this->getAllCategories();

        foreach ($categories as $category) {
            $objCategory = new Category();
            $objCategory->setName($category);

            $this->em->persist($objCategory);
        }

        $this->em->flush();
    }

    public function getAllCategories(): array
    {
        $categories = [];
        $htmlContent = $this->client->request('GET')->getBody()->getContents();

        $this->crawler = new Crawler($htmlContent);
        $catClasses = $this->crawler->filter('.icon-tag');

        /**
         * @var \DOMElement $cat
         */
        foreach ($catClasses as $cat) {
            $categories[] = $cat->textContent;
        }

        return $categories;
    }
}