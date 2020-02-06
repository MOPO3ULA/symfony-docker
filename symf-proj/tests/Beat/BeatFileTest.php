<?php

namespace App\Tests\Beat;

use App\Entity\Beat;
use PHPUnit\Framework\TestCase;

class BeatFileTest extends TestCase
{
    public function testThatComputerWorks(): void
    {
        $this->assertTrue(true);
    }

    public function testBeatLength(): void
    {
        $beat = new Beat();

        $this->assertNull($beat->getBeatLength());

        $beat->setBeatLength(1.5);

        $this->assertSame(1.5, $beat->getBeatLength());
    }
}