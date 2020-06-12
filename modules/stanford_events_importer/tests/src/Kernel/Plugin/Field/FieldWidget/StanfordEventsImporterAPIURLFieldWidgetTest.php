<?php

namespace Drupal\Tests\stanford_events_importer\Kernel\Plugin\Field\FieldWidget;

use Drupal\KernelTests\KernelTestBase;

/**
 * Class StanfordEventsImporterAPIURLFieldWidgetTest.php
 *
 * @group stanford_events_importer
 * @coversDefaultClass \Drupal\stanford_events_importer\Plugin\Field\FieldWidget\StanfordEventsImporterAPIURLFieldWidget
 */
class StanfordEventsImporterAPIURLFieldWidgetTest extends KernelTestBase {

  /**
   * {@inheritDoc}
   */
  protected static $modules = [
    'system',
    'path_alias',
    'node',
    'user',
    'link',
    'datetime',
    'field',
    'stanford_events_importer'
  ];

  /**
   * {@inheritDoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installEntitySchema('user');
    $this->installEntitySchema('node');
    $this->installEntitySchema('field_config');

    NodeType::create(['type' => 'page'])->save();

    $field_storage = FieldStorageConfig::create([
      'field_name' => 'field_xml_link',
      'entity_type' => 'node',
      'type' => 'link',
      'cardinality' => -1,
    ]);
    $field_storage->save();

    $field = FieldConfig::create([
      'field_name' => 'field_xml_link',
      'entity_type' => 'node',
      'bundle' => 'page',
    ]);
    $field->save();
  }

  /**
   * Test the entity form is displayed correctly.
   */
  public function testWidgetForm() {
    $node = Node::create([
      'type' => 'page',
    ]);
    /** @var \Drupal\Core\Entity\Display\EntityFormDisplayInterface $entity_form_display */
    $entity_form_display = EntityFormDisplay::create([
      'targetEntityType' => 'node',
      'bundle' => 'page',
      'mode' => 'default',
      'status' => TRUE,
    ]);
    $entity_form_display->setComponent('field_xml_link', ['type' => 'stanford_events_importer_apiurl_field_widget'])
      ->removeComponent('created')
      ->save();

    $form = [];
    $form_state = new FormState();

    $entity_form_display->buildForm($node, $form, $form_state);

    $widget_value = $form['field_xml_link']['widget'][0];
    $this->assertIsArray($widget_value);
  }

}
