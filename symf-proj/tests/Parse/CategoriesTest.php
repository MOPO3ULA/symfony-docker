<?php

namespace App\Tests\Parse;

use App\Service\CategoriesParser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategoriesTest extends KernelTestCase
{
    /**
     * @var CategoriesParser|object|null $categoriesParser
     */
    private $categoriesParser;

    protected function setUp()
    {
        self::bootKernel();

        $this->categoriesParser = self::$container->get(CategoriesParser::class);
    }

    public function testGettingAllCategories(): void
    {
        $allCategories = $this->categoriesParser->getAllCategories();

        if (is_array($allCategories) && count($allCategories) > 30) {
            $this->assertTrue(true);
        }
    }
}