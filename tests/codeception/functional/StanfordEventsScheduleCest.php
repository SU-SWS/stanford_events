<?php

class StanfordEventsScheduleCest {

    protected $node;

    public function _before(FunctionalTester $I) {
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
            'su_event_map_link' => 'https://stanford.edu/',
            'su_event_sponsor' => [
              'This is a sponsor',
              'This is two sponsor',
              'This is 3 sponsor',
            ],
            'su_event_subheadline' => 'This is a sub-headline',
          ];
          $e = $I->createEntity($values);
          $I->runDrush('cache:rebuild');
          $this->node = $e;
    }

    public function _after(FunctionalTester $I) {
        return;
    }

    public function testEventSchedule(FunctionalTester $I) {

      $I->logInWithRole('administrator');
        $path = $this->node->toUrl()->toString();
        $id = $this->node->id();

        $I->amOnPage("node/$id/edit");
        $I->canSee("Schedule Details");
        $I->click("Add Schedule");
        $I->wait(3);
        $I->seeElement('input', ['name' =>  'su_event_schedule[0][subform][su_schedule_headline][0][value]']);
        $I->fillField(['name' => 'su_event_schedule[0][subform][su_schedule_date_time][0][value][date]'], '12012021');
        $I->fillField(['name' => 'su_event_schedule[0][subform][su_schedule_date_time][0][value][time]'], '120101');
        $I->fillField(['name' => 'su_event_schedule[0][subform][su_schedule_headline][0][value]'], 'Scheduled Event Headline');
        $I->click('Save');
        $I->amOnPage($path);
        $I->canSee("Scheduled Event Headline");
        $I->canSee("Wednesday, December 1, 2021");
        $I->amOnPage("node/$id/edit");
        $I->click('Remove');
        $I->wait(3);
        $I->click('Confirm removal');
        $I->wait(1);
        $I->canSee('No Paragraph added yet');
        $I->click('Save');
        $I->runDrush('cache:rebuild');
        $I->amOnPage($path);
        $I->dontSee("Scheduled Event Headline");

    }

}
