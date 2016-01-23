
<?php
class LogViewerTest extends PHPUnit_Framework_TestCase
{
    public function testInit()
    {
        $translator = new \Syonix\ChangelogViewer\Translator\LabelTranslator('de');
        return $translator;
    }

    /**
     * @depends testInit
     */
    public function testTranslateFrom($translator)
    {
        $label = $translator->translateFrom('neu');
        $this->assertEquals('new', $label);
    }

    /**
     * @depends testInit
     */
    public function testTranslateTo($translator)
    {
        $label = $translator->translateTo('new');
        $this->assertEquals('neu', $label);
    }
}