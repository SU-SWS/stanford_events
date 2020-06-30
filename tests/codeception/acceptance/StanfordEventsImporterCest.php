<?php

class StanfordEventsImporterCest {

  /**
   * Events Importer Module Enable.
   */
  public function _before(AcceptanceTester $I) {
    $I->runDrush('pm:enable stanford_events_importer');
    drupal_static_reset();
    drupal_flush_all_caches();
  }

  /**
   * Test configuration form.
   */
  public function testForImporterForm(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/config/importers/events-importer");
    $I->canSeeResponseCodeIs(200);
    $I->canSee("Events Importer");
    $I->canSee("EVENTS TO BE IMPORTED");
  }

  /**
   * Test cron settings.
   */
  public function testForCronSettings(AcceptanceTester $I) {
    $I->logInWithRole("administrator");
    $I->amOnPage("/admin/config/system/cron/jobs");
    $I->canSee("Importer: Events");
    $I->amOnPage("/admin/config/system/cron/jobs/manage/stanford_migrate_stanford_events");
    $I->seeCheckboxIsChecked("#edit-status");
  }

}
