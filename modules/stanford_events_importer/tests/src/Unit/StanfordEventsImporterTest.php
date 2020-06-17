<?php

namespace Drupal\Tests\stanford_events_importer\Unit\Plugin\Field\FieldWidget;

use Drupal\stanford_events_importer\StanfordEventsImporter;
use Drupal\Tests\UnitTestCase;
use Drupal\Core\Cache\DatabaseBackend;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;


/**
 * Class StanfordEventsImporterTest
 *
 * @group stanford_events_importer
 * @coversDefaultClass \Drupal\stanford_events_importer\StanfordEventsImporter
 */
class StanfordEventsImporterTest extends UnitTestCase {

  /**
   * @var \Drupal\stanford_events_importer\StanfordEventsImporter
   */
  public $plugin;

  /**
   * @var \GuzzleHttp\ClientInterface
   */
  public $client;

  /**
   * @var string
   */
  public $xml;

  public function setup() {
    $mock = new MockHandler([
        new Response(200, [], '<CategoryList>
        <Category>
        <guid>0</guid>
        <categoryID>0</categoryID>
        <name>Arts</name>
        <type>3</type>
        <description>Arts</description>
        <tag>arts</tag>
        </Category>
        </CategoryList>'),
        new Response(200, [], '<OrganizationList>
        <Organization>
        <guid>279</guid>
        <organizationID>279</organizationID>
        <name>AASA</name>
        <type>2</type>
        <email>eshih@stanford.edu</email>
        <phone>510-366-6550</phone>
        <url>https://events.stanford.edu/byOrganization/279/</url>
        <rssUrl>https://events.stanford.edu/byOrganization/279/</rssUrl>
        </Organization>
        </OrganizationList>'),
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);
    $this->client = $client;
    $this->plugin = new StanfordEventsImporter($this->client);
  }

  /**
   * Test Fetch.
   */
  public function testFetchXML() {
    $this->xml = $this->plugin->fetchXML();
    $this->assertContains("Arts", $this->xml);

    $orgs = $this->plugin->fetchXML('organization-list');
    $this->assertContains("AASA", $orgs);
  }

  /**
   * Test Parse.
   */
  public function testParseXML() {

    $xml_string = <<<EOD
<CategoryList>
<Category>
<guid>0</guid>
<categoryID>0</categoryID>
<name>Arts</name>
<type>3</type>
<description>Arts</description>
<tag>arts</tag>
</Category>
<Category>
<guid>28</guid>
<categoryID>28</categoryID>
<name>Careers</name>
<type>2</type>
<description>Career Development</description>
<tag>careers</tag>
</Category>
<Category>
<guid>19</guid>
<categoryID>19</categoryID>
<name>Class</name>
<type>1</type>
<description>Classes</description>
<tag>class</tag>
</Category>
<Category>
<guid>3</guid>
<categoryID>3</categoryID>
<name>Conference / Symposium</name>
<type>1</type>
<description>Conferences / Symposia</description>
<tag>conference</tag>
</Category>
<Category>
<guid>5</guid>
<categoryID>5</categoryID>
<name>Dance</name>
<type>3</type>
<description>Dance</description>
<tag>dance</tag>
</Category>
</CategoryList>
EOD;

    $args = [
      'guids' => '/CategoryList/Category/guid',
      'label' => '/CategoryList/Category/name',
    ];
    $result = $this->plugin->parseXML($xml_string, $args);
    $this->assertIsArray($result);
    $this->assertEquals("Class", $result[19]);
  }

  /**
   * Make sure exception passes back false.
   * @return [type] [description]
   */
  public function testFetchXMLException() {
    // Create a mock and queue two responses.
    $mock = new MockHandler([
        new RequestException('Error Communicating with Server', new Request('GET', 'test'))
    ]);
    $handlerStack = HandlerStack::create($mock);
    $client = new Client(['handler' => $handlerStack]);
    $plugin = new StanfordEventsImporter($client);
    $val = $plugin->fetchXML();
    $this->assertFalse($val);
  }

  /**
   * Make sure exception passes back false.
   * @return [type] [description]
   */
  public function testParseXMLException() {
    $args = [
      'guids' => '/CategoryList/Category/guid',
      'label' => '/CategoryList/Category/name',
    ];
    $val = $this->plugin->parseXML('<root><item>stuff</item></root>', $args);
    $this->assertFalse($val);
  }

}
