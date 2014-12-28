<?php

/*
 * This file is part of the puli/repository-manager package.
 *
 * (c) Bernhard Schussek <bschussek@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Puli\RepositoryManager\Tests\Generator;

use PHPUnit_Framework_TestCase;
use Puli\RepositoryManager\Generator\FactoryCode;
use Puli\RepositoryManager\Generator\GeneratorFactory;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @since  1.0
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class AbstractGeneratorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $tempDir;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var string
     */
    protected $outputDir;

    /**
     * @var string
     */
    protected $outputPath;

    /**
     * @var GeneratorFactory
     */
    protected $generatorFactory;

    protected function setUp()
    {
        while (false === @mkdir($this->tempDir = sys_get_temp_dir().'/puli-repo-manager/KeyValueStoreDiscoveryGeneratorTest'.rand(10000, 99999), 0777, true)) {}

        $this->generatorFactory = new GeneratorFactory();
        $this->rootDir = $this->tempDir.'/root';
        $this->outputDir = $this->rootDir.'/out';
        $this->outputPath = $this->outputDir.'/generated.php';

        mkdir($this->rootDir);
        mkdir($this->outputDir);
    }

    protected function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove($this->tempDir);
    }

    protected function putCode($path, FactoryCode $code)
    {
        $imports = 'use '.implode(";\nuse ", $code->getImports()).";\n";
        $declarations = implode("\n", $code->getVarDeclarations());

        file_put_contents($path, "<?php\nnamespace Puli\\Test;\n$imports\n$declarations");

//        echo file_get_contents($path);
    }

    protected function assertCode($expected, FactoryCode $code)
    {
        $this->assertSame($expected, implode("\n\n", $code->getVarDeclarations()));
    }
}
