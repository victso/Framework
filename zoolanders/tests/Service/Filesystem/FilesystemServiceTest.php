<?php

namespace ZFTests\Service\Filesystem;

use ZFTests\TestCases\ZFTestCase;
use ZFTests\Classes\Filesystem;

/**
 * Class FilesystemServiceTest
 * Filesystem service traits and classes test
 *
 * @package ZFTests\Service\Filesystem
 */
class FilesystemServiceTest extends ZFTestCase
{
    /**
     * Test formating filesize
     *
     * @dataProvider    formatDataSet
     */
    public function testFormatSize($actual, $format, $expected){
        $file = new Filesystem(self::$container);
        $this->assertEquals($expected, $file->formatFilesize($actual, $format));
    }

    /**
     * Test filesize detection
     *
     * @depends         testFormatSize
     * @dataProvider    precizeFilesizeSet
     */
    public function testGetSize($src, $format, $expected){
        $file = new Filesystem(self::$container);
        $this->assertEquals($expected, $file->getSourceSize($src, $format));
    }

    /**
     * Test getting mimetype from file
     *
     * @dataProvider    difMimeTypesDataSet
     */
    public function testGetMimeType($src, $expected){
        $file = new Filesystem(self::$container);
        $this->assertEquals($expected, $file->getContentType($src));
    }

    /**
     * Test filenames cleanups
     *
     * @dataProvider    filenamesProvider
     */
    public function testMakeSafe($filename, $expected){
        $file = new Filesystem(self::$container);
        $this->assertEquals($expected, $file->makeSafe($filename));
    }

    /**
     * Test filepath cleanups
     *
     * @dataProvider    filepathsProvider
     */
    public function testPathCleanup($src, $expected){
        $file = new Filesystem(self::$container);
        $this->assertEquals($expected, $file->cleanPath($src));
    }

    /**
     * Test read directory files method
     *
     * @covers      Filesystem::readDirectoryFiles()
     */
    public function testReadDirectoryFiles(){

        $filteredFS = ['test1.txt', 'test2.txt'];
        $internalFS = ['subdir/test3.txt'];

        $file = new Filesystem(self::$container);
        $content = $file->readDirectoryFiles(JOOMLA_ENV_PATH . '/fixtures/filesystem');

        $this->assertArraySubset(array_merge($internalFS, $filteredFS), $content);

        // Now filter without subdirectories:
        $content = $file->readDirectoryFiles(JOOMLA_ENV_PATH . '/fixtures/filesystem', '', false, false);
        $this->assertArraySubset($filteredFS, $content);

        // Now filter by name:
        $content = $file->readDirectoryFiles(JOOMLA_ENV_PATH . '/fixtures/filesystem', '', '~test3~i');
        $this->assertArraySubset($internalFS, $content);
    }

    /**
     * Test read directory files method
     *
     * @covers      Filesystem::readDirectory()
     */
    public function testReadDirectory(){
        $file = new Filesystem(self::$container);
        $content = $file->readDirectory(JOOMLA_ENV_PATH . '/fixtures/filesystem');

        $this->assertArraySubset(['subdir'], $content);

        // Test filtering
        $content = $file->readDirectory(JOOMLA_ENV_PATH . '/fixtures/filesystem', '', '~folder~i');

        $this->assertArraySubset([], $content);
    }

    /**
     * Test get ext function
     *
     * @covers          Filesystem::getExtension()
     * @dataProvider    filesExtDataSet
     */
    public function testGetExtension($filename, $ext){
        $file = new Filesystem(self::$container);
        $this->assertEquals($ext, $file->getExtension($filename));
    }

    /**
     * Test makepath function
     *
     * @covers          Filesystem::makePath()
     * @dataProvider    makepathDataSet
     */
    public function testMakePath($arg1, $arg2, $expected){
        $file = new Filesystem(self::$container);
        $this->assertEquals($expected, $file->makePath($arg1, $arg2));
    }

    /**
     * Filesize data provider based on simple fixtured files
     */
    public function precizeFilesizeSet(){
        return [
           [ 'fixtures/filesystem/test1.txt', true, '13 B' ],
           [ 'fixtures/filesystem/test1.txt', false, 13 ],
           [ 'fixtures/filesystem/test2.txt', false, 10 ],
           [ 'fixtures/filesystem', true, '23 B' ],
        ];
    }

    /**
     * Provides pointers to files with different mimetypes
     */
    public function difMimeTypesDataSet(){
        return [
            ['test1.txt', 'text/plain'],
            ['script.js', 'application/x-javascript'],
            ['style.css', 'text/css'],
            ['samplepath', 'application/octet-stream'],
            ['test.pdf', 'application/pdf'],
        ];
    }

    /**
     * Format data provider
     */
    public function formatDataSet(){
        return [
            [10000, false, '9.77 KB'],
            [10000, 'KB', '9.77 KB'],
            [10000, true, '10000 B'],
            [1048576, 'MB', '1 MB'],
            [1000000, 'MB', '0.95 MB'],
            [1073741824, 'GB', '1 GB'],
            [1073741824, 'MB', '1024 MB'],
            [107374182400, 'TB', '0.1 TB']
        ];
    }

    /**
     * Filenames data provider
     */
    public function filenamesProvider(){
        return [
            ['some filename.txt', 'some_filename.txt'],
            ['~some+=?weird!@#$%^&*()-_filename.php', '~someweird-_filename.php'],
            ['111somefilename.txt', '111somefilename.txt'],
        ];
    }

    /**
     * Weird Filepaths data provider
     */
    public function filepathsProvider(){
        return [
            ['/some/related/path/', '/some/related/path/'],
            ['/some/weird///path.txt', '/some/weird/path.txt'],
        ];
    }

    /**
     * Files and ext dataset
     */
    public function filesExtDataSet(){
        return [
            ['index.html', 'html'],
            ['controller.php', 'php'],
            ['image.jpeg', 'jpeg'],
            ['document.pdf', 'pdf'],
            ['innerpath/file.txt', 'txt'],
            ['come_folder', '']
        ];
    }

    /**
     * Dataset for testing makepath func.
     */
    public function makepathDataSet(){
        return [
            [ 'folder', 'container', 'folder/container' ],
            [ 'folder/', 'container', 'folder/container' ],
            [ '/folder/', '/container', '/folder/container' ]
        ];
    }
}
