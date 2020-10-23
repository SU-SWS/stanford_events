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
      var today = new Date();

      // so that today is all of the day
      var a = today.setHours(0, 0, 0, 0);
      var b = today.setHours(23, 59, 59, 1);
      var todayBegin = parseInt(a, 10);


      // go thru page looking for the date
      const eventType = $('div.node-stanford-event-su-event-type .su-event-type');
      const eventDate = $('div.node-stanford-event-su-event-date-time');
      const eventTextLocation = $('div.node-stanford-event-su-event-date-time');

      const pastLabel = $('<span class="su-event-label-past">Past Event <span class="divider">|</span></span>');
      const pastText = $('<div class="su-event-text-past">This event has passed.</div>');

      // check the date on the page.
      const contentDate = parseInt(Date.parse($('div.su-event-date', eventDate).text()), 10);

      // apply past label and text
      if (contentDate < todayBegin) {
        pastLabel.prependTo(eventType.first());
        pastText.appendTo(eventTextLocation.last());
      }

    })(jQuery);
  }
};
