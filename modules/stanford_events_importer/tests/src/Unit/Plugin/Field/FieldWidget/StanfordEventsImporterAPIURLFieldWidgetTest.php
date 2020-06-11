<?php

namespace Drupal\Tests\stanford_events_importer\Unit\Plugin\Field\FieldWidget;

use Drupal\Core\Form\FormState;
use Drupal\stanford_events_importer\Plugin\Field\FieldWidget\StanfordEventsImporterAPIURLFieldWidget;
use Drupal\Tests\UnitTestCase;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;

/**
 * Class StanfordEventsImporterAPIURLFieldWidget
 *
 * @group stanford_events_importer
 * @coversDefaultClass \Drupal\stanford_events_importer\Plugin\Field\FieldWidget\StanfordEventsImporterAPIURLFieldWidget
 */
class StanfordEventsImporterAPIURLFieldWidgetTest extends UnitTestCase {

  /**
   * The fieldWidget Plugin.
   */
  public $plugin;

  /**
   * The field def.
   */
  public $field_definition;

  /**
   * Test.
   */
  public function setup() {
    $plugin_id = "stanford_events_importer_apiurl_field_widget";

    $plugin_definition = [
      "field_types" => ["link"],
      "multiple_values" => false,
      "id" => "stanford_events_importer_apiurl_field_widget",
      "module" => "stanford_events_importer",
      "label" => "Label",
      "class" => "Drupal\stanford_events_importer\Plugin\Field\FieldWidget\StanfordEventsImporterAPIURLFieldWidget",
       "provider" => "stanford_events_importer",
    ];

    $field_storage = new FieldStorageConfig([
      "langcode" => "en",
      "status" => true,
      "dependencies" => [
        "module" => [
          "config_pages",
          "field_permissions",
          "link",
        ],
      ],
      "third_party_settings" => [
        "field_permissions" => [
          "permission_type" => "public",
        ],
      ],
      "id" => "config_pages.su_event_xml_url",
      "field_name" => "su_event_xml_url",
      "entity_type" => "config_pages",
      "type" => "link",
      "settings" => [],
      "module" => "link",
      "locked" => false,
      "cardinality" => -1,
      "translatable" => true,
      "indexes" => [],
      "persist_with_no_fields" => false,
      "custom_storage" => false,
    ]);

    $field_definition = new FieldConfig([
      "langcode" => "en",
      "deleted" => false,
      "fieldStorage" => $field_storage,
      "status" => true,
      "dependencies" => [
        "config" => [
          "config_pages.type.stanford_events_importer",
          "field.storage.config_pages.su_event_xml_url",
        ],
        "module" => [
          "link",
        ],
      ],
      "id" => "config_pages.stanford_events_importer.su_event_xml_url",
      "field_name" => "su_event_xml_url",
      "entity_type" => "config_pages",
      "bundle" => "stanford_events_importer",
      "label" => 'Events to be imported',
      "description" => '',
      "required" => true,
      "translatable" => false,
      "default_value" => [],
      "default_value_callback" => '',
      "settings" => [
        "link_type" => 16,
        "title" => 0,
      ],
      "field_type" => "link"
    ]);

    $this->field_definition = $field_definition;

    $settings = [
      "placeholder_url" => "",
      "placeholder_title" => "",
    ];

    $third_party_settings = [];

    // Make it.
    $this->plugin = new StanfordEventsImporterAPIURLFieldWidget($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
  }

  /**
   * Test
   */
  public function testMassageFormValues() {
    $values = [
      [
        "_other" => [
          'type' => 'organization',
          'org_status' => 'bookmarked',
          'organization' => 23,
        ],
      ],
      [
        "_other" => [
          'type' => 'category',
          'category' => 23,
        ],
      ],
      [
        "_other" => [
          'type' => 'featured',
        ],
      ],
      [
        "_other" => [
          'type' => 'today',
        ],
      ],

    ];

    $form = [];
    $form_state = new FormState();
    $results = $this->plugin->massageFormValues($values, $form, $form_state);
    $this->assertEquals("https://events.stanford.edu/xml/drupal/v2.php?organization=23&bookmarked", $results[0]['uri']);
    $this->assertEquals("https://events.stanford.edu/xml/drupal/v2.php?category=23", $results[1]['uri']);
    $this->assertEquals("https://events.stanford.edu/xml/drupal/v2.php?featured", $results[2]['uri']);
    $this->assertEquals("https://events.stanford.edu/xml/drupal/v2.php?today", $results[3]['uri']);

  }

  /**
   * Test
   */
  public function testIsApplicable() {
    $this->assertTrue(StanfordEventsImporterAPIURLFieldWidget::isApplicable($this->field_definition));

  }

}

