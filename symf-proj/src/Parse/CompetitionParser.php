<?php

namespace App\Parse;


use App\Validate\CompetitionValidator;
use DOMElement;
use Symfony\Component\DomCrawler\Crawler;

class CompetitionParser
{
    private const neededTags = [
        'bpm',
        'genre',
        'category',
        'size',
        'keyCreatedWith',
        'createdWith'
    ];

    private const allTags = [
        'bpm',
        'genre',
        'category',
        'size',
        'type',
        'keyCreatedWith',
        'createdWith'
    ];

    /**
     * @var Crawler $crawler
     */
    public $crawler;

    private const tagWordToCut = [
        'bpm' => ' bpm',
        'genre' => ' Loops',
        'category' => ' Loops',
        'keyCreatedWith' => 'Key : '
    ];

    public function setCrawler(string $htmlContent): CompetitionParser
    {
        $this->crawler = new Crawler($htmlContent);

        return $this;
    }

    public function getHtmlCountOfSamples(): string
    {
        return $this->crawler->filter('.pagination-counters')->html();
    }

    public function getObjectsOfSamplesOnPage()
    {
        return $this->crawler->filter('.player-title');
    }

    public function getHrefString(DOMElement $element): string
    {
        return $element->getAttribute('href');
    }

    public function getSampleParameters(): array
    {
        $audioBlock = $this->crawler->filter('.player-wrapper')->first();

        $sampleParameters['file'] = $this->getLink($audioBlock);
        $sampleParameters['title'] = $this->getTitle();
        $sampleParameters['user'] = [
            'name' => $this->getUsername(),
            'link' => $this->getUserLink()
        ];
        $sampleParameters['length'] = $this->getLength($audioBlock);
        $sampleParameters['description'] = $this->getDescription();

        $tagsString = $this->getTags();

        /**
         * @var DOMElement $tag
         */
        foreach ($tagsString as $key => $tag) {
            $tagText = $tag->textContent;

            if (in_array(self::allTags[$key], self::neededTags, true)) {
                if (array_key_exists(self::allTags[$key], self::tagWordToCut)) {
                    $tagText = str_replace(self::tagWordToCut[self::allTags[$key]], '', $tagText);
                    $sampleParameters[self::allTags[$key]] = $tagText;
                } else {
                    $sampleParameters[self::allTags[$key]] = $tagText;
                }
            }
        }

        return $sampleParameters;
    }

    public function getLoopermanLink(): string
    {
        return CompetitionValidator::validateString(
            $this->crawler->filter('.player-title')->attr('href')
        );
    }

    private function getLink(Crawler $audioBlock): string
    {
        return CompetitionValidator::validateString(
            $audioBlock->attr('rel')
        );
    }

    private function getTitle(): string
    {
        return CompetitionValidator::validateString(
            $this->crawler->filter('.player-title')->text()
        );
    }

    private function getUsername(): string
    {
        return CompetitionValidator::validateString(
            $this->crawler->filter('.icon-user')->text()
        );
    }

    private function getUserLink(): string
    {
        return CompetitionValidator::validateString(
            $this->crawler->filter('.link-user-verified')->attr('href')
        );
    }

    private function getDescription(): string
    {
        $description = $this->crawler->filter('.desc-wrapper')->first()->text();
        return CompetitionValidator::validateDescription($description);
    }

    private function getLength(Crawler $audioBlock): string
    {
        $textLength = $audioBlock->filter('.jp-time-wrapper')->text();
        return CompetitionValidator::validateLength($textLength);
    }

    private function getTags()
    {
        return $this->crawler->filter('.tag-wrapper')->first()->filter('a');
    }
}