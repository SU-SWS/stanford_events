<?php

namespace Drupal\Tests\stanford_events_importer\Unit\Plugin\migrate\process;

use Drupal\stanford_events_importer\Plugin\migrate\process\DateMath;
use Drupal\migrate\MigrateExecutableInterface;
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
   * [protected description]
   * @var [type]
   */
  public function testTransform() {
    $end_value = "2022-06-11 00:00:00 -0700";
    $value = "2022-06-11 01:00:00 -0700";

    $configuration = [
      'operation' => 'subtraction',
      'values' => [
        $end_value
      ],
    ];

    $plugin = new DateMath($configuration, '', []);
    $migrate = $this->createMock(MigrateExecutableInterface::class);
    $row = $this->createMock(Row::class);
    $duration = $plugin->transform($value, $migrate, $row, '');

    $this->assertEquals(60, $duration);
  }

}
