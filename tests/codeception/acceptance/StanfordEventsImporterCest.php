<?php

class StanfordEventsImporterCest {

  /**
   * Events Importer Module Enable.
   */
  public function EnableModule(AcceptanceTester $I) {
    $I->runDrush('pm:enable stanford_events_importer');
  }

}
