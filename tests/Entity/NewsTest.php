<?php


namespace App\Tests\Entity;

use App\Entity\News;
use PHPUnit\Framework\TestCase;
class NewsTest extends TestCase
{
    public function testUri()
    {
        $article = new News();
        $uri = "Test 2";

        $article->setUri($uri);
        $this->assertEquals("test_2", $article->getUri());
    }
}