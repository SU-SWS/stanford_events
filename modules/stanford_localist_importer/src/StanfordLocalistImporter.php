<?php

namespace Drupal\stanford_localist_importer;

use GuzzleHttp\ClientInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * A class to do some importery stuff.
 */
class StanfordLocalistImporter {


  /**
   * @var string
   */
  protected $apiUrl;

  /**
   * Guzzle client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  private $client;

  /**
   * StanfordEventsImporter constructor.
   *
   * @param \GuzzleHttp\ClientInterface $http_client
   *   A Guzzle Like HTTP client.
   */
  public function __construct(ClientInterface $http_client, string $api_url) {
    $this->client = $http_client;
    $this->apiUrl = $api_url;
  }

  /**
   * Get JSON from the API for a feed.
   *
   * @return string|bool
   *   Full string of raw xml.
   */
  public function fetchFeedJson() {

    $options = [
      'headers' => [
        'Accept' => 'application/json',
      ],
    ];

    try {
      $request = $this->client->request('GET', $this->apiUrl, $options);
      return (string) $request->getBody();
    }
    catch (\Exception $e) {
      return FALSE;
    }

  }

  /**
   * Parses the raw JSON feed return value from the API.
   *
   * Turns the raw xml return value into a key value pair and saves it into the
   * state for the site.
   *
   * @return array|bool
   *   Success or not.
   */
  public function parseFeed($raw) {

    // Do we need to parse this here?

    /*
    $xml = new \SimpleXMLElement($raw);
    $guids = $xml->xpath($options['guids']);
    $labels = $xml->xpath($options['label']);

    array_walk($guids, function (&$val) {
      $val = $val->__toString();
    });

    array_walk($labels, function (&$val) {
      $val = $val->__toString();
    });

    // Do nothing if nothing was found.
    if (empty($guids) || empty($labels)) {
      return FALSE;
    }

    // Create an associative array.
    return array_combine($guids, $labels);
    */
  }

}
