<?php

/**
 * Class StanfordEventsSeriesCest.
 *
 * @group stanford_events
 * @group stanford_events_series
 */
class StanfordEventsSeriesCest {

  /**
   * Events Series Module Enable.
   */
  public function _before(AcceptanceTester $I) {
    // Log in and enable the module through the UI.
    $I->logInWithRole('administrator');
    $I->amOnPage('/admin/modules');
    $enabled = $I->grabAttributeFrom('input[name="modules[stanford_events_series][enable]"]', 'checked');
    if (!$enabled) {
      $I->checkOption('Stanford Events Series');
      $I->click('Install');
      $I->click('Continue');
    }
    $I->amOnPage('/admin/structure/types/manage/stanford_event_series/fields');
    $I->canSeeResponseCodeIs(200);
    $I->amOnPage('/user/logout');
  }

  /**
   * Test admin.
   */
  public function testEventSeriesNodeFieldsAsAdmin(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage('/admin/structure/types/manage/stanford_event_series/fields');
    $I->canSee('su_event_series_components');
    $I->canSee('su_event_series_dek');
    $I->canSee('su_event_series_event');
    $I->canSee('su_event_series_subheadline');
    $I->canSee('su_event_series_type');
    $I->canSee('su_event_series_weight');
  }

  /**
   * Test for create node and view display.
   */
  public function testCreateNewNode(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $node = $this->createEventSeriesNode($I);
    $I->amOnPage($node->toUrl()->toString());
    $I->canSee("This is a test event series node");
    $I->canSee("Series Event Node: 0");
    $I->canSee("Series Event Node: 1");
    $I->canSee("Series Event Node: 2");
    $I->canSee("Series Event Node: 3");
    $I->canSee("Series Event Node: 4");
    $I->canSee("Series Event Node: 5");
  }

  /**
   * Test Path Auto Settings.
   */
  public function testEventSeriesPathAuto(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $node = $this->createEventSeriesNode($I, "Test Test Test");
    $I->amOnPage($node->toUrl()->toString());
    $I->canSeeInCurrentUrl('/event/series/test-test-test');
  }

  /**
   * Creates an event series node.
   */
  protected function createEventSeriesNode(AcceptanceTester $I, $node_title = NULL) {
    $event_nodes = [];
    for ($i = 0; $i <= 5; $i++) {
      $node = $this->createEventNode($I, "Series Event Node: $i", $i);
      $event_nodes[] = ['target_id' => $node->id()];
    }

    $e = $I->createEntity([
      'type' => 'stanford_event_series',
      'title' => $node_title ?: 'This is a test event series node',
      'su_event_series_components' => [],
      'su_event_series_dek' => "This is a dek",
      'su_event_series_event' => $event_nodes,
      'su_event_series_subheadline' => "This is a subheadline",
    ], 'node', FALSE);
    $I->runDrush('cache:rebuild');
    return $e;
  }

  /**
   * Create event nodes.
   */
  protected function createEventNode(AcceptanceTester $I, $node_title = NULL, $time_multiplier = 1) {
    $start = time() - (60 * 60 * 24 * $time_multiplier);
    $end = time() + (60 * 60 * 24 * $time_multiplier);

    $e = $I->createEntity([
      'type' => 'stanford_event',
      'title' => $node_title ?: 'This is a test event node',
      'body' => [
        "value" => "<p>More updates to come.</p>",
        "summary" => "",
      ],
      'su_event_date_time' => [
        'value' => $start,
        'end_value' => $end,
        'duration' => floor(($start - $end) / 60),
        'timezone' => "America/Los_Angeles",
      ],
      'su_event_dek' => 'This is a dek field',
      'su_event_sponsor' => [
        'This is a sponsor',
      ],
      'su_event_subheadline' => 'This is a sub-headline',
    ]);
    $I->runDrush('cache:rebuild');
    return $e;
  }

  /**
   * Test for Views.
   */
  public function testForViewsExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/views");
    $I->canSee("stanford_event_series");
  }

  /**
   * Test for Page Manager Pages.
   */
  public function testForPageManagerPagesExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    // Admin.
    $I->amOnPage("/admin/structure/page_manager");
    $I->canSee("event_series");
    // Front End.
    $I->amOnPage("/event-series");
    $I->canSeeResponseCodeIs(200);
  }

}
