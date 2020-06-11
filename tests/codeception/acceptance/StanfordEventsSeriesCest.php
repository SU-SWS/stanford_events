<?php

class StanfordEventsSeriesCest {

  /**
   * Events Series Module Enable.
   */
  public function EnableModule(AcceptanceTester $I) {
    $I->runDrush('pm:enable stanford_events_series');
  }

  /**
   * @depends EnableModule
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
   *
   * @depends EnableModule
   */
  public function testCreateNewNode(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $node = $this->createEventSeriesNode($I);
    $I->runDrush("cr");
    $path = $node->toUrl()->toString();
    $I->amOnPage($path);
    $I->canSee("This is a test event series node");
    $I->canSee("Series Event Node: 0");
    $I->canSee("Series Event Node: 1");
    $I->canSee("Series Event Node: 2");
    $I->canSee("Series Event Node: 3");
    $I->canSee("Series Event Node: 4");
    $I->canSee("Series Event Node: 5");
  }

  /**
   * @depends EnableModule
   */
  public function testEventSeriesPathAuto(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $node = $this->createEventSeriesNode($I, "Test Test Test");
    $I->runDrush("cr");
    $path = $node->toUrl()->toString();
    $I->assertEquals($path, "/event/series/test-test-test");
  }

  /**
   * Creates an event series node.
   *
   * @depends EnableModule
   */
  protected function createEventSeriesNode(AcceptanceTester $I, $node_title = NULL) {
    $event_nodes = [];
    for($i = 0; $i <= 5; $i++) {
      $node = $this->createEventNode($I, "Series Event Node: $i", $i);
      $event_nodes[] = ['target_id' => $node->id()];
    }

    return $I->createEntity([
      'type' => 'stanford_event_series',
      'title' => $node_title ?: 'This is a test event series node',
      'su_event_series_event' => $event_nodes,
    ]);
  }

   /**
    * [protected description]
    * @var [type]
    */
   protected function createEventNode(AcceptanceTester $I, $node_title = null, $time_multiplier = 1) {
     $start = time() - (60 * 60 * 24 * $time_multiplier);
     $end = time() + (60 * 60 * 24 * $time_multiplier);

     return $I->createEntity([
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
   }

   /**
    * Test for Views.
    *
    * @depends EnableModule
    */
   public function testForViewsExist(AcceptanceTester $I) {
     $I->logInWithRole('administrator');
     $I->amOnPage("/admin/structure/views");
     $I->canSee("stanford_event_series");
   }

   /**
    * Test for Page Manager Pages.
    *
    * @depends EnableModule
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
