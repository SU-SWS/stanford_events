<?php

namespace Drupal\Tests\stanford_events_importer\Unit\Plugin\migrate\process;

use Drupal\Core\DependencyInjection\Container;
use Drupal\stanford_events_importer\Plugin\migrate\process\DateMath;
use Drupal\migrate\MigrateExecutable;
use Drupal\migrate\Plugin\MigrateDestinationInterface;
use Drupal\migrate\Plugin\MigratePluginManager;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;
use Drupal\Tests\UnitTestCase;

/**
 * Class DateMathTest.
 *
 * @group stanford_events_importer
 * @coversDefaultClass \Drupal\stanford_events_importer\Plugin\migrate\process\DateMath
 */
class DateMathTest extends UnitTestCase {

  /**
   * @var \Drupal\Core\DependencyInjection\Container
   */
  protected $container;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $container = new Container();
    $container->set('plugin.manager.migrate.process', $this->createMock(MigratePluginManager::class));

    $this->container = $container;
  }

  /**
   * [protected description]
   * @var [type]
   */
  public function testTransform() {
    $value = time();
    $migration = $this->createMock(MigrationInterface::class);
    $migration->method('getDestinationPlugin')
      ->willReturn($this->createMock(MigrateDestinationInterface::class));
    $row = $this->createMock(Row::class);

    $configuration = [
      'operation' => 'subtraction',
      'values' => [
        $value + (60 * 60)
      ],
    ];
    $definition = [];
    $plugin = DateMath::create($this->container, $configuration, 'stanford_events_datemath', $definition, $migration);

    $duration = $plugin->transform($value, $migration, $row, '');
    $this->assertEquals(60, $duration);
  }

}
