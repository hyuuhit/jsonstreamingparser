<?php

require_once dirname(__FILE__).'/../example/DocumentCompleteListener.php';
require_once dirname(__FILE__).'/../example/MultiDocumentsListener.php';

use \JsonStreamingParser\Parser;
class MultiDocumentsListenerTest extends \PHPUnit_Framework_TestCase implements \DocumentCompleteListener {

    private $verifyfile;

    public function document_complete($doc) {
        // Parse using json_decode
        $expected = json_decode(file_get_contents($this->verifyfile), true);

        // Make sure the two produce the same object structure
        $this->assertSame($expected, $doc);
    }

    public function testExampleTwiceJson() {
        $testfile = dirname(__FILE__).'/../example/example_twice.json';
        $verifyfile = dirname(__FILE__).'/../example/example.json';
        $this->assertParsesCorrectly($testfile, $verifyfile);
    }

    public function testGeoJson() {
        $testfile = dirname(__FILE__).'/../example/geojson/example_twice.geojson';
        $verifyfile = dirname(__FILE__).'/../example/geojson/example.geojson';
        $this->assertParsesCorrectly($testfile, $verifyfile);
    }

    private function assertParsesCorrectly($testfile, $verifyfile) {
        $this->verifyfile = $verifyfile;
        $listener = new \MultiDocumentsListener($this);
        $stream = fopen($testfile, 'r');
        try {
            $parser = new Parser($stream, $listener, "\n", false, 8192, true);
            $parser->parse();
            fclose($stream);
        } catch (Exception $e) {
            fclose($stream);
            throw $e;
        }

    }
}
