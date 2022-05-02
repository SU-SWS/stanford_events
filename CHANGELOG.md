# Stanford Events


8.x-1.26
--------------------------------------------------------------------------------
_Release Date: 2022-05-02_

- Updated dev dependencies (#111)
- Removed duplicate dependencies in composer.json
- D8CORE-5801: fixed white background color (#110)
- D8CORE-5719 D8CORE-5720 D8CORE-5724 D8CORE-5726 D8CORE-5722 D8CORE-5727 ALLY improvements to minicalendar
- D8CORE-4128 Adjusted styles to change views to HTML lists (#108)
- D8CORE-5663 D8CORE-5660 D8CORE-5664 Improve accessibility of the mini calendar (#109)
- shared tags (#107)


8.x-1.24
--------------------------------------------------------------------------------
_Release Date: 2022-03-23_

- D8CORE-5649: fix to the hover on the current date. (#105)


8.x-1.23
--------------------------------------------------------------------------------
_Release Date: 2022-03-17_

- Updated views_infinite_scroll module (#103)
- D8CORE-5102 Events mini-calendar using PDB (#101)
- Removed D8 Tests


8.x-1.22
--------------------------------------------------------------------------------
_Release Date: 2022-03-04_

- D8CORE-3990: Modified template and styles to replace <p> with <div> (#96)
- Upgrade smartdate module (#97)


8.x-1.21
--------------------------------------------------------------------------------
_Release Date: 2022-01-27_

- D8CORE-5000: Constraining the components to the 10-of-12 col width (#92)
- Merge branch 'master' into 8.x-1.x
- Merge branch 'master' into 8.x-1.x
- Added localist URL to external source field. (#90)


8.x-1.19
--------------------------------------------------------------------------------
_Release Date: 2022-01-20_

- D8CORE-5172 Update old event importer for events-legacy domain.


8.x-1.18
--------------------------------------------------------------------------------
_Release Date: 2021-11-19_

- D8CORE-4521 Localist events importer (#87)
- D8CORE-3497: fixing title alignment of the more events (#86)


8.x-1.17
--------------------------------------------------------------------------------
_Release Date: 2021-09-03

- D8CORE-3161: reduce load more button size (#83)
- D8CORE-4643: updating the skip links and moving to one filter by menu (#80)
- D8CORE-4667: Removed duplicate date read out for screen readers.  (#82)
- D8CORE-4186: removing pipe if not neeeded (#81)
- D8CORE-2520: date labeling for Events (#79)

8.x-1.16
--------------------------------------------------------------------------------
_Release Date: 2021-07-09_

- D8CORE-4508: adding skip to main on the topic menu (#77) (486bad9)
- D8CORE-4236: fixing the event date aligment on cards and lists of card or default list (#76) (e5da211)
- D8CORE-3984 Fixed the person CTA template (7e707a1)
- D8CORE-4378: Adding the skip to secondary nav link (#75) (db902bd)

8.x-1.15
--------------------------------------------------------------------------------
_Release Date: 2021-06-15_

- Fixed event card pattern by passing all available variables to the list pattern

8.x-1.14
--------------------------------------------------------------------------------
_Release Date: 2021-06-11_

- D8CORE-4173: changed from padding to margin to fix up the event cards (#71) (e380d0f)
- D8CORE-3162: Changing the headline text size for events in cards and lists (#72) (a79fd98)
- D8CORE-2520 Refactored templates and use full month for screen readers (#70) (821ccd3)

8.x-1.13
--------------------------------------------------------------------------------
_Release Date: 2021-05-07_

- D8CORE-3991: Fixing the grey on the Past events to be accessible. (#67) (0116955)
- D8CORE-3984 Display the speaker image if available (#68) (84829b7)
- D8CORE-3950: correcting the title spacing (#66) (beba945)

8.x-1.12
--------------------------------------------------------------------------------
_Release Date: 2021-04-09_

- D8CORE-4033: Added past events viewfield with filters (#64) (5a65de6)
- D8CORE-3938: styling the subhead on the list of events (#62) (c46a0cb)

8.x-1.11
--------------------------------------------------------------------------------
_Release Date: 2021-03-17_

- Make sure the padding-right only appears on the cards when in lg breakpoint. This comes from the events card css.

8.x-1.10
--------------------------------------------------------------------------------
_Release Date: 2021-03-15_

- Fixed special characters in the templates.

8.x-1.9
--------------------------------------------------------------------------------
_Release Date: 2021-03-05_

- D8CORE-3352: styles for past events page (#58) (5749d96)
- D8CORE-3463 Adjust the list and cards displays (#54) (5305ad0)
- Move patches to shared package (#56) (31b138a)

8.x-1.8
--------------------------------------------------------------------------------
_Release Date: 2021-02-16_

- Updated views taxonomy term name depth patch (#53) (ec1a381)

8.x-1.7
--------------------------------------------------------------------------------
_Release Date: 2021-02-08_

- D8CORE-2968: Fix for deleted schedule items still appearing (#50) (efcfae0)
- D8CORE-3451: fixing the date aligment for events on lists (#51) (7816cd4)
- D8CORE-3182: change the mailto and extlink icons to match text (#49) (65898f8)
- D8CORE-2750: fixing the heading spacing (#48) (e079e8b)
- Updated circleci testing (#47) (cacaf5f)

8.x-1.6
--------------------------------------------------------------------------------
_Release Date: 2020-12-04_

- D8CORE-2326: Modified the event schedule date formatting to account for multiple days. (#45) (e47d913)
- D8CORE-2325: Added separator for date field. (#44) (104cea1)
- D8CORE-2967: fixing the past event label to work with articles without type (#41) (2dab23a)
- Updated default content module (#43) (6002147)
- Update tests for D9 phpunit (#42) (bf71a36)
- phpunit void return annoation (ed55621)

8.x-1.5
--------------------------------------------------------------------------------
_Release Date: 2020-10-09_

- D8CORE-2873-v2: fixing the date format (#39)
- D8CORE-2306: removed sorting controls in the event schedule entity reference field. (#35)
- D8CORE-2873: Adding the Past label (#32)
- D8CORE-2930 D8CORE-2924: fixing the event card display and removing duplicate code (#36)
- D8CORE-000 Event card views stack on smaller screens. (#34)
- D8CORE-2737 Event card display mode and view list. (#29)

8.x-1.4
--------------------------------------------------------------------------------
_Release Date: 2020-09-14_

- Fixed composer dependencies.

8.x-1.3
--------------------------------------------------------------------------------
_Release Date: 2020-09-09_

- Merge pull request #25 from SU-SWS/D8CORE-2499 (124293f)
- D8CORE-2499 Updated composer license (d618682)
- Merge pull request #24 from SU-SWS/D8CORE-2337 (817179d)
- D8CORE-2337 D8CORE-2370: changing col with to 8 from 9 (da9df14)
- Merge pull request #23 from SU-SWS/D8CORE-2327 (c267116)
- D8CORE-2327: fixed the date alignment (28600c6)

8.x-1.2
--------------------------------------------------------------------------------
_Release Date: 2020-08-07_

- DEVOPS-000: https://www.drupal.org/project/ds/issues/3154727 Patch merged in (#21) (8ae5226)
- Removed views_taxonomy_term_name_depth thats not used (#20) (738af45)

8.x-1.1
--------------------------------------------------------------------------------
_Release Date: 2020-07-13_

- D8CORE-2317: fix to the missing location on events (#17) (0ef6f0e)
- D8CORE-2319: changed the padding on the list items to MS 4 like news (#16) (e7beede)
- D8CORE-2318: removing the link action. list items shouldn't have it (#14) (329bd5e)
- D8CORE-2228: adding in the nav region with the correct names (#10) (7c28f62)
- D8CORE-2218: Patched DS for empty grouping title. (#12) (d1985cb)
- D8CORE-2223: Add smart_date patch to prevent duplicate timezones. (#13) (8788675)
- D8CORE-2290: moving the CTA button in the events node page. (#15) (35a24ac)
- D8CORE-2070: increasing and making MS margin consistent for accelerant headers (#11) (a3e7ae6)
- D8CORE-2265: adding styles for past events scroll button (#9) (8bc9bfb)

8.x-1.0
--------------------------------------------------------------------------------
_Release Date: 2020-06-17_

- Initial Release.
