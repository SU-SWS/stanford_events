<?php

/**
 * Class StanfordEventsImporterCest.
 *
 * @group stanford_events
 * @group stanford_events_importers
 */
class StanfordEventsImporterCest {

  /**
   * Events Importer Module Enable.
   */
  public function _before(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage('/admin/modules');
    $enabled = $I->grabAttributeFrom('input[name="modules[stanford_events_importer][enable]"]', 'checked');
    if (!$enabled) {
      $I->checkOption('Stanford Events Importer');
      $I->click('Install', '.form-actions');
      if ($I->grabMultiple('input[value="Continue"]')) {
        $I->click('Continue');
      }
    }
    $I->amOnPage('/user/logout');
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
    $I->amOnPage("/admin/config/system/cron/jobs/manage/stanford_migrate_stanford_events_importer");
    $I->canSeeResponseCodeIs(200);
    $I->canSee("stanford_migrate_ultimate_cron_task");
    $I->seeCheckboxIsChecked("#edit-status");
  }

}
