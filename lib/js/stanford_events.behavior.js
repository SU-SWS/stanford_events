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

      var todayMilliseconds = today.getTime(); // today in milliseconds

      // so that today is all of the day
      var a = today.setHours(0, 0,0,0);
      var b = today.setHours(23, 59, 59, 1);
      var todayBegin = parseInt(a, 10);
      var todayEnd = parseInt(b, 10);


      // go thru page looking for the date
        const eventType = $('div.node-stanford-event-su-event-type');
        const eventDate = $('div.node-stanford-event-su-event-date-time');

        const futureLabel = $('<span class="su-event-future">Future Event</span>');
        const pastLabel = $('<span class="su-event-past">Past Event</span>');
        const todayLabel = $('<span class="su-event-past">Todays Event</span>');

        const defaultLabel = $('<span class="su-event-default">default</span>');

        // check the date on the page.
        const contentDate = parseInt(Date.parse($('div.su-event-date', eventDate).text()),10);


        if (contentDate > todayEnd ) {
          futureLabel.appendTo(eventType);
        }
        else if (contentDate < todayBegin) {
          pastLabel.appendTo(eventType);
        }
        else if (contentDate >= todayBegin && contentDate <= todayEnd) {
          todayLabel.appendTo(eventType);
        }
        else {
          defaultLabel.appendTo(eventType);
        }

    })(jQuery);
  }
};
