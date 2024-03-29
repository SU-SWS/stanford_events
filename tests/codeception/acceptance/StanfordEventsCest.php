<?php

/**
 * Class StanfordEventsCest.
 *
 * @group stanford_events
 */
class StanfordEventsCest {

  /**
   * Events Node Fields as Admin.
   */
  public function testEventNodeFieldsAsAdmin(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage('/admin/structure/types/manage/stanford_event/fields');
    $I->canSee('su_event_audience');
    $I->canSee('body');
    $I->canSee('su_event_cta');
    $I->canSee('su_event_components');
    $I->canSee('su_event_email');
    $I->canSee('su_event_telephone');
    $I->canSee('su_event_date_time');
    $I->canSee('su_event_dek');
    $I->canSee('su_event_alt_loc');
    $I->canSee('su_event_type');
    $I->canSee('su_event_source');
    $I->canSee('su_event_location');
    $I->canSee('su_event_map_link');
    $I->canSee('su_event_schedule');
    $I->canSee('su_event_sponsor');
    $I->canSee('su_event_subheadline');
  }

  /**
   * Test for create node and view display.
   */
  public function testCreateNewNode(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $node = $this->createEventNode($I);
    $path = $node->toUrl()->toString();
    $I->amOnPage($path);
    $I->canSee("This is a headline");
    $I->canSee("More updates to come.");
  }

  /**
   * Test to make sure teaser view is set to the list pattern.
   */
  public function testViewTeaser(AcceptanceTester $I) {
    $I->logInWithRole("administrator");
    $I->amOnPage("/admin/structure/types/manage/stanford_event/display/teaser");
    $I->canSeeResponseCodeIs(200);
    $I->seeOptionIsSelected("#edit-ds-layout", "Events List Item");
  }

  /**
   * Test for display suite fields.
   */
  public function testDSFieldsExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/ds/fields");
    $I->canSee("Date - End Day");
    $I->canSee("Date - End Month");
    $I->canSee("Date - Start Day");
    $I->canSee("Date - Start Month");
  }

  /**
   * Test for pathauto Node
   */
  public function testForPathAutoNode(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $node = $this->createEventNode($I, FALSE, ['title' => "Stanford Jumpstart"]);
    $path = $node->toUrl()->toString();
    $I->assertEquals("/event/stanford-jumpstart", $path);
  }

  /**
   * Test for pathauto Terms
   */
  public function testForPathAutoTerm(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $term = $this->createEventTypeTerm($I, "Test Test Test");
    $path = $term->toUrl()->toString();
    $I->assertEquals("/events/test-test-test", $path);
  }

  /**
   * Test for Defaut content.
   */
  public function testForDefaultContent(AcceptanceTester $I) {
    $I->logInWithRole('administrator');

    // Event Audience.
    $I->amOnPage("/admin/structure/taxonomy/manage/event_audience/overview");
    $I->canSee("Students");
    $I->canSee("Alumni/Friends");
    $I->canSee("Members");
    $I->canSee("General Public");
    $I->canSee("Faculty/Staff");

    // Event Type.
    $I->amOnPage("/admin/structure/taxonomy/manage/stanford_event_types/overview");
    $I->canSee("Lecture / Reading");
    $I->canSee("Conference / Symposium");
    $I->canSee("Seminar");

    // Events Main Menu Link.
    $I->amOnPage("/admin/structure/menu/manage/stanford-event-types");
    $I->canSee("All Upcoming Events");
  }

  /**
   * Test for Views.
   */
  public function testForViewsExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/views");
    $I->canSee("stanford_events_past");
    $I->canSee("stanford_events_schedule");
    $I->canSee("stanford_event_terms_utility");
  }

  /**
   * Test for Page Manager Pages.
   */
  public function testForPageManagerPagesExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/page_manager");

    // Admin.
    $I->canSee("stanford_events_list");
    $I->canSee("stanford_events_upcoming");
    $I->canSee("stanford_events_past");

    // Front End.
    $I->amOnPage("/events");
    $I->canSeeResponseCodeIs(200);
    $I->amOnPage("/events/past");
    $I->canSeeResponseCodeIs(200);
    $I->amOnPage("/events/conference-symposium");
    $I->canSeeResponseCodeIs(200);
  }

  /**
   * Test for Internal Content.
   */
  public function testForInternalEventContent(AcceptanceTester $I) {
    $node = $this->createEventNode($I, FALSE, ['title' => "Internal Event"]);
    $I->runDrush('cr');
    $path = $node->toUrl()->toString();
    $I->amOnPage($path);
    $I->canSeeResponseCodeIs(200);
    $I->seeCurrentUrlEquals('/event/internal-event');
    $I->canSee("Internal Event");
    $I->dontSee("This page will redirect to");
  }

  /**
   * Test for External content event.
   */
  public function testforExternalEventContent(AcceptanceTester $I) {
    $node = $this->createEventNode($I, TRUE, ['title' => "External Event"]);
    $I->runDrush('cr');
    $path = $node->toUrl()->toString();

    // Anon.
    $I->amOnPage($path);
    $I->seeCurrentUrlEquals('/events/880/88074');

    // Admin.
    $I->logInWithRole('administrator');
    $I->amOnPage($path);
    $I->canSeeResponseCodeIs(200);
    $I->seeCurrentUrlEquals('/event/external-event');
    $I->canSee("External Event");
    $I->canSee("This page will redirect to");
  }

  /**
   * Test for Smart Date formats.
   */
  public function testForSmartDateFormats(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/config/regional/smart-date");
    $I->canSee("Time Only - No All Day");
    $I->canSee("Stanford Events Long");
  }

  /**
   * Test for Taxonomy Groups.
   */
  public function testForTaxonomyVocabularies(AcceptanceTester $I) {
    $I->logInWithRole('administrator');

    // Event Audience.
    $I->amOnPage("/admin/structure/taxonomy/manage/event_audience/overview");
    $I->canSeeResponseCodeIs(200);

    // Event Type.
    $I->amOnPage("/admin/structure/taxonomy/manage/stanford_event_types/overview");
    $I->canSeeResponseCodeIs(200);
  }

  /**
   * Test for paragraphs.
   */
  public function testForParagraphsExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/admin/structure/paragraphs_type");
    $I->canSee("stanford_person_cta");
    $I->canSee("stanford_schedule");

    // Schedule Fields.
    $I->amOnPage("/admin/structure/paragraphs_type/stanford_schedule/fields");
    $I->canSee("su_schedule_date_time");
    $I->canSee("su_schedule_description");
    $I->canSee("su_schedule_headline");
    $I->canSee("su_schedule_location");
    $I->canSee("su_schedule_speaker");
    $I->canSee("su_schedule_url");

    // Person CTA Fields.
    $I->amOnPage("/admin/structure/paragraphs_type/stanford_person_cta/fields");
    $I->canSee("su_person_cta_image");
    $I->canSee("su_person_cta_link");
    $I->canSee("su_person_cta_name");
    $I->canSee("su_person_cta_title");
  }

  /**
   * Test for patterns.
   */
  public function testForPatternsExist(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage("/patterns");

    $I->canSee("Event Schedule");
    $I->canSee("Events List Item");
    $I->canSee("Person CTA");
  }

  /**
   * Test to ensure new event type terms show up in menu.
   */
  public function testForTaxonomyMenuAutoAdd(AcceptanceTester $I) {
    $this->createEventTypeTerm($I);
    $I->runDrush("cr");
    $I->amOnPage("/events");
    $I->canSeeLink("Foo");
  }

/**
 * Timezone format rendering.
 *
 * https://www.drupal.org/project/smart_date/issues/3154741
 */
  public function testForDateFormatTimezone(AcceptanceTester $I) {
    $date = strtotime("Tuesday, July 28, 2020 1:00pm PDT");
    $vals = ['su_event_date_time' => [
      'value' => $date,
      'end_value' => $date + (60 * 60),
      'duration' => (60),
      'timezone' => "America/Los_Angeles",
    ]];

    $node = $this->createEventNode($I, FALSE, $vals);
    $path = $node->toUrl()->toString();
    $I->amOnPage($path);
    $I->canSee("Tuesday, July 28, 2020");
    $I->canSee("1:00pm - 2:00pm PDT");
  }

  /**
   * Create an Event Node.
   *
   * @param AcceptanceTester $I
   *   Codeception AcceptanceTester
   * @param bool $external
   *   Wether or not this node should be external.
   * @param string $content
   *   A key/value array for field names and content.
   *
   * @return object
   *   Node Object
   */
  protected function createEventNode(AcceptanceTester $I, $external = FALSE, $content = []) {
    $values = [
      'type' => 'stanford_event',
      'title' => 'This is a headline',
      'body' => [
        "value" => "<p>More updates to come.</p>",
        "summary" => "",
      ],
      'su_event_cta' => [
        "uri" => "https://google.com/",
        "title" => "This is cta link text",
      ],
      'su_event_email' => 'noreply@stanford.edu',
      'su_event_telephone' => '555-555-5645',
      'su_event_date_time' => [
        'value' => time(),
        'end_value' => time() + (60 * 60 * 24),
        'duration' => (60 * 24),
        'timezone' => "America/Los_Angeles",
      ],
      'su_event_dek' => 'This is a dek field',
      'su_event_alt_loc' => $external ? "https://events-legacy.stanford.edu/" : "",
      'su_event_source' => $external ? [
        "uri" => "http://events-legacy.stanford.edu/events/880/88074",
        "title" => "",
      ] : "",
      'su_event_location' => $external ?: [
        "langcode" => "",
        "country_code" => "US",
        "administrative_area" => "CA",
        "locality" => "San Francisco",
        "postal_code" => "94123-2806",
        "address_line1" => "1901 Lombard St",
        "address_line2" => "",
        "organization" => "Asfdasdfa sdfasd fasf",
      ],
      'su_event_map_link' => 'https://stanford.edu/',
      'su_event_sponsor' => [
        'This is a sponsor',
        'This is two sponsor',
        'This is 3 sponsor',
      ],
      'su_event_subheadline' => 'This is a sub-headline',
    ];
    $values = array_merge($values, $content);
    $e = $I->createEntity($values);
    $I->runDrush('cache:rebuild');
    return $e;
  }

  /**
   * Creates a new event type term.
   *
   * @param AcceptanceTester $I
   *   Codeception AcceptanceTester
   * @param string $name
   *   The name of the term.
   *
   * @return object
   *   A taxonomy term.
   */
  protected function createEventTypeTerm(AcceptanceTester $I, $name = NULL) {
    $e = $I->createEntity([
      'name' => $name ?: 'Foo',
      'vid' => 'stanford_event_types',
    ], 'taxonomy_term');
    $I->runDrush('cache:rebuild');
    return $e;
  }

  /**
   * Creates a new event audience term.
   *
   * @param AcceptanceTester $I
   *   Codeception AcceptanceTester
   * @param string $name
   *   The name of the term.
   *
   * @return object
   *   A taxonomy term.
   */
  protected function createEventAudienceTerm(AcceptanceTester $I, $name = NULL) {
    $e = $I->createEntity([
      'name' => $name ?: 'Foo',
      'vid' => 'stanford_event_audience',
    ], 'taxonomy_term');
    $I->runDrush('cache:rebuild');
    return $e;
  }

}
