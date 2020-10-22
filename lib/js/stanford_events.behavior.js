/**
 * Behavior Example that works with Webpack.
 *
 * @see: https://www.npmjs.com/package/drupal-behaviors-loader
 *
 * Webpack wraps everything in enclosures and hides the global variables from
 * scripts so special handling is needed.
 */

export default {

  // Attach Drupal Behavior.
  attach: function (context, settings) {
    (function ($) {
      $('.event-types__collapsable-menu', context).click(function () {
        $(this).toggleClass('show');
        if ($(this).siblings('.menu').css('display') !== 'none') {
          $(this).attr('aria-expanded', 'true');
        } else {
          $(this).attr('aria-expanded', 'false');
        }
      });

      // get today's date
      const today = new Date();
      console.log(today);

      // go thru page looking for the date
      $('div.body-region').each(function () {
        const eventType = $('div.node-stanford-event-su-event-type');

        // check the date on the page.
        const divDate = Date.parse($('div.su-event-date', this).text());

        console.log(divDate);

        switch (divDate) {
          case divDate > today:
            eventType.addClass('future');
            break;
          case divDate < today:
            eventType.addClass('past');
            break;
          default:
            eventType.addClass('today');
            break;
        }
      });

    })(jQuery);
  }
};
