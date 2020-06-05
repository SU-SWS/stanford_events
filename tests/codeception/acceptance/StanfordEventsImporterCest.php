<?php

class StanfordEventsImporterCest {

  /**
   * Events
   */
  public function testEventNodeForm(AcceptanceTester $I) {
    $I->logInWithRole('administrator');
    $I->amOnPage('/node/add/stanford_event');
    $I->canSee('Event Title');
    $I->canSee('Event Type');
    $I->canSee('Subheadline');
    $I->canSee('Dek');
    $I->canSee('SPONSOR');
    $I->canSee('Body');
    $I->canSee('Components');
    $I->canSee('DATE & TIME');
    $I->canSee('External Source');
    $I->canSee('LOCATION');
    $I->canSee('MAP LINK');
    $I->canSee('Contact Email');
    $I->canSee('Contact Telephone');
    $I->canSee('Audience');
    $I->canSee('CALL TO ACTION BUTTON');
    $I->canSee('Schedule');
  }

}
