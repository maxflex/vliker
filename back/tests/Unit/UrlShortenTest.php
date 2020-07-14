<?php

namespace Tests\Unit;

use App\Utils\Url;
use PHPUnit\Framework\TestCase;

class UrlShortenTest extends TestCase
{
    public function testSubscribe()
    {
        $url = "https://vk.com/club197115227";
        $result = Url::shorten($url);
        $this->assertEquals($result, "club197115227");
    }
}
