<?php

namespace App\Parse;


use App\Validate\CompetitionValidator;
use Symfony\Component\DomCrawler\Crawler;

class CompetitionParser extends BaseParser
{
    private const tagsReferences = [
        'bpm',
        'genre',
        'category',
        'size',
        'type',
        'key',
        'createdWith'
    ];
    private const tagWordToCut = [
        'bpm' => ' bpm',
        'genre' => ' Loops',
        'category' => ' Loops',
        'key' => 'Key : '
    ];

    public function getHtmlCountOfSamples(): string
    {
        return $this->crawler->filter('.pagination-counters')->html();
    }

    public function getObjectsOfSamplesOnPage()
    {
        return $this->crawler->filter('.player-title');
    }

    public function getHrefString(\DOMElement $element): string
    {
        return $element->getAttribute('href');
    }

    public function getBeatParameters(): array
    {
        $audioBlock = $this->crawler->filter('.player-wrapper')->first();

        $beatParameters['link'] = $this->getLink($audioBlock);
        $beatParameters['title'] = $this->getTitle();
        $beatParameters['user'] = [
            'username' => $this->getUsername(),
            'link' => $this->getUserLink()
        ];
        $beatParameters['length'] = $this->getLength($audioBlock);
        $beatParameters['description'] = $this->getDescription();

        $tagsString = $this->getTags();

        /**
         * @var $tag \DOMElement
         */
        foreach ($tagsString as $key => $tag) {
            $tagText = $tag->textContent;

            if (array_key_exists(self::tagsReferences[$key], self::tagWordToCut)) {
                $tagText = str_replace(self::tagWordToCut[self::tagsReferences[$key]], '', $tagText);
            }

            $beatParameters[self::tagsReferences[$key]] = $tagText;
        }

        return $beatParameters;
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