<?php

namespace App\Parse;


use Symfony\Component\DomCrawler\Crawler;

class BaseParser
{
    protected $crawler;

    public function __construct(string $htmlContent)
    {
        $this->crawler = new Crawler($htmlContent);
    }
}