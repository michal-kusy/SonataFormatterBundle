<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\FormatterBundle\Tests\Formatter;

use PHPUnit\Framework\TestCase;
use Sonata\FormatterBundle\Formatter\TwigFormatter;
use Twig\Environment;
use Twig\Loader\LoaderInterface;
use Twig\Source;

class TwigFormatterTest extends TestCase
{
    /**
     * NEXT_MAJOR: Remove the group when deleting FormatterInterface.
     *
     * @group legacy
     */
    public function testFormatter(): void
    {
        $loader = new MyStringLoader();
        $twig = new Environment($loader);

        $formatter = new TwigFormatter($twig);

        // Checking, that formatter can process twig template, passed as string
        $this->assertSame('0,1,2,3,', $formatter->transform('{% for i in range(0, 3) %}{{ i }},{% endfor %}'));

        // Checking, that formatter does not changed loader
        if (class_exists('\Twig_Loader_String')) {
            $this->assertNotInstanceOf('\\Twig_Loader_String', $twig->getLoader());
        }
        $this->assertInstanceOf('Sonata\\FormatterBundle\\Tests\\Formatter\\MyStringLoader', $twig->getLoader());
    }

    public function testAddFormatterExtension(): void
    {
        $this->expectException(\RuntimeException::class);

        $loader = new MyStringLoader();
        $twig = new Environment($loader);

        $formatter = new TwigFormatter($twig);

        $formatter->addExtension(new \Sonata\FormatterBundle\Extension\GistExtension());
    }

    public function testGetFormatterExtension(): void
    {
        $loader = new MyStringLoader();
        $twig = new Environment($loader);

        $formatter = new TwigFormatter($twig);

        $extensions = $formatter->getExtensions();

        $this->assertCount(0, $extensions);
    }
}

class MyStringLoader implements LoaderInterface
{
    public function getSourceContext(string $name): Source
    {
        return new Source('', $name);
    }

    public function getCacheKey(string $name): string
    {
        return $name;
    }

    public function isFresh(string $name, int $time): bool
    {
        return true;
    }

    public function exists(string $name)
    {
        return true;
    }
}
