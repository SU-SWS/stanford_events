<?php

class StanfordEventsSeriesCest {

  /**
   * Events Series Module Enable.
   */
  public function testEnableModule(AcceptanceTester $I) {
    $I->runDrush('pm:enable stanford_events_series');
  }

}
