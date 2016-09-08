<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\MinkExtension\Context\MinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use Centreon\Test\Behat\CentreonContext;
use Centreon\Test\Behat\HostConfigurationPage;
use Centreon\Test\Behat\ServiceConfigurationPage;
use Centreon\Test\Behat\ConfigurationPollersPage;

/**
 * Defines application features from the specific context.
 */
class InfluxdbContext extends CentreonContext
{
  private $hostName = 'InfluxdbTestHost';
  private $serviceName = 'InlufxdbTestService';
  
    /**
     *  @Given I am logged in a Centreon server with Influxdb
     */
    public function aCentreonServerWithInfluxdb()
    {
        $this->launchCentreonWebContainer('web_influxdb');
        $this->iAmLoggedIn();
    }
  
    /**
     * @Given an Influxdb output is properly configured
     */
    public function anInfluxdbOutputIsProperlyConfigured()
    {
      $this->visit('main.php?p=60909&o=c&id=1');
      $this->assertFind('css', 'li#c4 > a:nth-child(1)')->click();
      file_put_contents('/tmp/test.png', $this->getSession()->getDriver()->getScreenshot());
      $this->assertFind('css', 'select#block_output')->selectOption('InfluxDB - Storage - InfluxDB');
      $this->assertFind('css', 'a#add_output')->click();
      sleep(5);
      $this->assertFind('named', array('id', 'output[4][name]'))->setValue('TestInfluxdb');
      $this->assertFind('named', array('id', 'output[4][db_host]'))->setValue('127.0.0.1');
      $this->assertFind('named', array('id', 'output[4][metrics_timeseries]'))->setValue('metric.$HOST$.$SERVICE$');
      $this->assertFind('named', array('id', 'output[4][status_timeseries]'))->setValue('status.$HOST$.$SERVICE$');
      $this->assertFind('css', '#metrics_column___4_template0 > td:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(3) > td:nth-child(2) > input:nth-child(1)')->setValue('test');
      $this->assertFind('css', '#metrics_column___4_template0 > td:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(4) > td:nth-child(2) > input:nth-child(1)')->setValue('test');
      $this->assertFind('css', '#status_column___4_template0 > td:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(3) > td:nth-child(2) > input:nth-child(1)')->setValue('test');
      $this->assertFind('css', '#status_column___4_template0 > td:nth-child(1) > table:nth-child(1) > tbody:nth-child(1) > tr:nth-child(4) > td:nth-child(2) > input:nth-child(1)')->setValue('test');
      $this->assertFind('css', '#validForm > p:nth-child(1) > input:nth-child(1)')->click();
    }

    /**
     *  @Given a passive service is configured
     */
    public function aPassiveServiceIsConfigured() {
      $hostConfig = new HostConfigurationPage($this);
      $hostProperties = array(
        'name' => $this->hostName,
        'alias' => $this->hostName,
        'active_checks_enabled' => "0",
        'passive_checks_enabled' => "1"
      );
      $hostConfig->setProperties($hostProperties);
      $hostConfig->save();
      
      $serviceConfig = new ServiceConfigurationPage($this);
      $serviceProperties = array(
        'description' => $this->serviceName,
        'hosts' => $this->hostName,
        'active_checks_enabled' => "0",
        'passive_checks_enabled' => "1"
      );
      $serviceConfig->setProperties($serviceProperties);
      $serviceConfig->save();
    }
    
    /**
     * @Given Broker and Engine are restarted
     */
    public function andBrokerAndEngineAreRestarted()
    {
      (new ConfigurationPollersPage($this))->restartEngine();
    }

    /**
     * @When new metric data is discovered by the engine for the service
     */
    public function whenNewMetricDataIsDiscoveredByTheEngine()
    {
      $this->submitServiceResult($this->hostName, $this->serviceName, 'OK', '', 'test=1s;5;10;0;10');
      sleep(5);
    }


    /**
     * @Then it is saved in Influxdb
     */
    public function thenItIsSavedInInfluxdb()
    {
      $this->spin(function($context) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, '127.0.0.1:2003');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return (false);
      });
    }
}