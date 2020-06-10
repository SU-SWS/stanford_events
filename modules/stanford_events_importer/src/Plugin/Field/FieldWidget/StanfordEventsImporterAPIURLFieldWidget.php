<?php

namespace Drupal\stanford_events_importer\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\link\LinkItemInterface;
use Drupal\Component\Utility\UrlHelper;
use Drupal\link\Plugin\Field\FieldWidget\LinkWidget;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Field\FieldDefinitionInterface;

/**
 * Plugin implementation of the 'stanford_events_importer_apiurl_field_widget' widget.
 *
 * @FieldWidget(
 *   id = "stanford_events_importer_apiurl_field_widget",
 *   module = "stanford_events_importer",
 *   label = @Translation("Stanford Events API URL Builder Widget"),
 *   field_types = {
 *     "link"
 *   }
 * )
 */
class StanfordEventsImporterAPIURLFieldWidget extends LinkWidget {

  /**
   * Path to feed xml.
   */
  const XML_FEED = "https://events.stanford.edu/xml/drupal/v2.php";

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {

    // De-falt.
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['#attached']['library'][] = 'core/drupal.states';

    /** @var \Drupal\link\LinkItemInterface $item */
    $item = $items[$delta];
    $defaults = $this->parseURLForDefaults($item);

    // Add a type of feed.
    $element['_other']['type'] = [
      '#type' => 'select',
      '#title' => $this->t("Event Group Option"),
      '#options' => [
        '' => $this->t("- Select Option -"),
        'organization' => $this->t("Organization"),
        'category' => $this->t("Category"),
        'featured' => $this->t('Featured'),
        'today' => $this->t('Today'),
      ],
      '#default_value' => $defaults['type'] ?? '',
    ];

    // The organization id (integer) as provided in the xml feed.
    $element['_other']['organization'] = [
      '#type' => 'select',
      '#title' => $this->t("Organization"),
      '#field_parents' => ['other'],
      '#options' => $this->getOrgOptions(),
      '#states' => [
        'visible' => [
          '#edit-su-event-xml-url-' . $delta . '-other-type' => ['value' => 'organization'],
        ],
      ],
      '#default_value' => $defaults['organization'] ?? '',
    ];

    $element['_other']['category'] = [
      '#type' => 'select',
      '#title' => $this->t("Category"),
      '#field_parents' => ['other'],
      '#options' => $this->getCatOptions(),
      '#states' => [
        'visible' => [
          '#edit-su-event-xml-url-' . $delta . '-other-type' => ['value' => 'category'],
        ],
      ],
      '#default_value' => $defaults['category'] ?? '',
    ];

    $element['_other']['org_status'] = [
      '#type' => 'select',
      '#title' => $this->t("Event Status"),
      '#field_parents' => ['other'],
      '#options' => [
        'published' => $this->t("Published"),
        'unlisted' => $this->t("Unlisted"),
        'bookmarked' => $this->t("Bookmarked"),
      ],
      '#states' => [
        'visible' => [
          '#edit-su-event-xml-url-' . $delta . '-other-type' => [ 'value' => 'organization' ],
        ],
      ],
      '#default_value' => $defaults['org_status'] ?? '',
    ];

    // Hide the uri.
    $element['uri']['#type'] = "hidden";
    $element['uri']['#required'] = FALSE;
    return $element;
  }

  /**
   * [getOrgOptions description]
   * @return [type] [description]
   */
  protected function getOrgOptions() {
    $opts = \Drupal::state()->get("stanford_events_importer_orgs");

    // If state doesn't exist. Try to get it.
    if (empty($opts)) {
      stanford_events_importer_update_opts();
      $opts = \Drupal::state()->get("stanford_events_importer_orgs");
    }

    array_unshift($opts, $this->t("- Select Organization -"));
    return $opts;
  }

  /**
   * [getCatOptions description]
   * @return [type] [description]
   */
  protected function getCatOptions() {

    $opts = \Drupal::state()->get("stanford_events_importer_cats");

    // If state doesn't exist. Try to get it.
    if (empty($opts)) {
      stanford_events_importer_update_opts();
      $opts = \Drupal::state()->get("stanford_events_importer_cats");
    }

    array_unshift($opts, $this->t("- Select Category -"));
    return $opts;
  }


  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {

    // Parse form options we added into a url for the events.stanford.edu feed.
    array_walk($values, function(&$value) {
      $type = $value['_other']['type'] ?? '';
      $val = $value['_other'][$type] ?? '';
      $extra = $value["_other"]['org_status'] ?? '';
      unset($value['_other']);

      // Empty value. Empty out uri and do not parse a url. This is how you get
      // rid of an option.
      if ($val === "" || $type === "") {
        $value['uri'] = "";
        return;
      }

      // Valid Data. Create a url for the uri column.
      $url = static::XML_FEED . "?" . $type . "=" . $val;

      // Organizations have extra options.
      if ($type == "organization") {
        $url .= "&" . $extra;
      }

      // Set the value.
      $value['uri'] = $url;
    });

    // Let the parent LinkWidget do its thing.
    $values = parent::massageFormValues($values, $form, $form_state);

    // Finish.
    return $values;
  }

  /**
   * Parse a link item for XML_FEED values.
   *
   * @param \Drupal\link\LinkItemInterface $item
   *
   * @return array
   * @throws \Drupal\Core\TypedData\Exception\MissingDataException
   */
  protected function parseURLForDefaults(LinkItemInterface $item) {
    $parsed = [];
    $uri = $item->get('uri')->getValue();

    // Nothing to do.
    if (empty($uri)) {
      return $parsed;
    }

    // Break up the URL to get at the query strings.
    $parts = UrlHelper::parse($uri);

    // Pull apart the query strings and set them to keys for easy use.
    if (isset($parts['query'])) {
      $parsed = $parts['query'];
      $keys = array_keys($parts['query']);
      $parsed['type'] = array_shift($keys);
      $parsed['org_status'] = array_pop($keys);
    }

    // Return back the broken down information.
    return $parsed;
  }

  /**
   * {@inheritdoc}
   */
  public static function isApplicable(FieldDefinitionInterface $field_definition) {
    // By default, widgets are available for all fields. Limit to just one.
    $allow = [ 'su_event_xml_url' ];
    $field_name = $field_definition->getFieldStorageDefinition()->get('field_name');
    return in_array($field_name, $allow);
  }


}
