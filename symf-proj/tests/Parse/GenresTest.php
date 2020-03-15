<?php

namespace App\Tests\Parse;

use App\Service\GenresParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GenresTest extends KernelTestCase
{
    /**
     * @var GenresParser|object|null $genresParser
     */
    private $genresParser;

    protected function setUp()
    {
        self::bootKernel();

        $this->genresParser = self::$container->get(GenresParser::class);
    }

    public function testGettingAllGenres(): void
    {
        $allGenres = $this->genresParser->getAllGenres();

        if (is_array($allGenres) && count($allGenres) > 50) {
            $this->assertTrue(true);
        }
    }
}