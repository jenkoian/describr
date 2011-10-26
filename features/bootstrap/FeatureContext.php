<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//
require_once 'C:/xampp/htdocs/www/boxuk/describr/lib/bootstrap.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext {

    protected $output;
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param   array   $parameters     context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters) {
        // Initialize your context here
    }

    
   /**
    * @Given /^I have a file called "([^"]*)"$/
    */
   public function iHaveAFileCalled($filename) {
       $this->filename = $filename;
   }

   /**
    * @When /^I describe the file$/
    */
   public function iDescribeTheFile() {
       $this->describr = new \BoxUK\Describr\Facade();
       $this->output = $this->describr->describeFile($this->filename);
   }

   /**
    * @Then /^I should get an array containing an extension of: "([^"]*)"$/
    */
   public function iShouldGetAnArrayContainingAnExtensionOf($extension) {
       $results = $this->output->getPluginResults('BoxUK\General');
       $key = array_search($extension, $results);

       if ($key !== 'extension') {
           throw new Exception(
               "Actual extension is:\n" . $results['extension']
           );
       }
   }
}
