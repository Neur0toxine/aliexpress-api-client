<?php

/**
 * PHP version 7.3
 *
 * @category TopRequestFactoryTest
 * @package  RetailCrm\Tests\Factory
 */
namespace RetailCrm\Tests\Factory;

use RetailCrm\Component\Constants;
use RetailCrm\Factory\TopRequestFactory;
use RetailCrm\Interfaces\TopRequestFactoryInterface;
use RetailCrm\Model\Enum\AvailableSignMethods;
use RetailCrm\Test\TestCase;

/**
 * Class TopRequestFactoryTest
 *
 * @category TopRequestFactoryTest
 * @package  RetailCrm\Tests\Factory
 */
class TopRequestFactoryTest extends TestCase
{
    public function testFromModelGet(): void
    {
        /** @var TopRequestFactory $factory */
        $factory = $this->getContainer()->get(TopRequestFactoryInterface::class);
        $request = $factory->fromModel(
            $this->getTestRequest(AvailableSignMethods::HMAC_MD5),
            $this->getAppData()
        );
        $uri = $request->getUri();
        $contents = self::getStreamData($request->getBody());

        self::assertNotEmpty($contents);
        self::assertFalse(stripos($contents, 'simplify'), $uri->getQuery());
        self::assertNotFalse(stripos($contents, 'SPAIN_LOCAL_CORREOS'));
    }

    public function testFromModelPost(): void
    {
        /** @var TopRequestFactory $factory */
        $factory = $this->getContainer()->get(TopRequestFactoryInterface::class);
        $request = $factory->fromModel(
            $this->getTestRequest(AvailableSignMethods::HMAC_MD5, true, true),
            $this->getAppData()
        );
        $uri = $request->getUri();
        $contents = self::getStreamData($request->getBody());

        self::assertEmpty($uri->getQuery());
        self::assertNotFalse(stripos($contents, 'The quick brown fox jumps over the lazy dog'));
        self::assertNotFalse(stripos($contents, '{"modelContent":"contentData"}'));
    }
}
