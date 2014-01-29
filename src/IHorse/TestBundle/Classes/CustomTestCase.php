<?php

namespace IHorse\TestBundle\Classes;


use IHorse\TestBundle\Util\FileHelper;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Tools\SchemaTool;
use \Doctrine\Common\Collections\Collection;

abstract class CustomTestCase extends WebTestCase
{
    const USERNAME = "jiminy@cricket.com";
    const PASSWORD = "conscience";

    protected $container;
    protected $application;
    protected $client;
    protected $environment;

    protected function setUp()
    {
        $options = array('environment' => 'test', 'debug' => true,);

        $this->client = static::createClient($options, 
                        array('HTTP_HOST' => 'horse.bo.me/app_dev.php/'));
        $this->container = static::$kernel->getContainer();

        parent::setUp();
    }


    protected function tearDown()
    {
        $this->removeIntegrationTestObjects();
        parent::tearDown();
        $reflect = new \ReflectionClass($this);
        $properties = $reflect->getProperties();
        foreach ($properties as $p) {
            $attr = $p->getName();
            if ($p->isStatic()) {
                $p->setAccessible(1);
                $p->setValue(null);
            } else if (!is_array($this->$attr)) {
                $this->$attr = null;
            }
        }
    }

    /**
     * Return the service asked
     * @param string $service
     *
     * @return $service
     */
    protected function get($service)
    {
        return $this->container->get($service);
    }

    protected function printContent()
    {
        ldd($this->client->getResponse()->getContent());
    }

    protected function removeIntegrationTestObjects()
    {
        //template method
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["-e"] = "test";
        $options["-q"] = null;
        $options = array_merge($options, array('command' => $command));

        return $this->application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }

    protected function assertEqualsCollections(Collection $c1, Collection $c2, $fieldToCompare)
    {
        $fieldName = 'get' . ucfirst($fieldToCompare);

        $this->assertEquals($c1->count(), $c2->count());
        for ($i = 0; $i < $c1->count(); $i++) {
            $this->assertEquals($c1[$i]->$fieldName(), $c2[$i]->$fieldName());
        }
    }

    /**
     * Load the given fixtures
     * @param string $fixture the name of the file in which is defined the class which implements FixtureInterface
     * @param string $setDataFixtureFunction name of the callback function which create the objects from the read data. Default case implemented
     *
     * @return mixed $data Adapted to the needs of the test
     */
    protected function loadFixture($fixture, $setDataFixtureFunction = 'setDataFixture')
    {
        $extension = explode('.', $fixture);
        $extension = $extension[count($extension)-1];

        $loadFunction = "load" . ucfirst($extension);
        $data = FileHelper::$loadFunction($fixture);

        return $this->$setDataFixtureFunction($data);
    }

    /**
     * Function needed to set some $_SERVER variables in order
     * to run some tests for BackOffice.
     */
    protected function setBOParameters()
    {
        $_SERVER['HTTP_HOST'] = $this->client->getServerParameter('HTTP_HOST');
        $_SERVER['REQUEST_URI'] = '/app_dev.php/bo';
    }

    protected function getLastLineFromFile($filePath)
    {
        $file = file_get_contents($filePath);
        $lines = explode("\n", $file);
        array_pop($lines);

        return $line = $lines[count($lines) - 1];
    }
}
