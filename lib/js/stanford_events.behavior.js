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
    (function ($, Drupal) {
      $('.event-types__collapsable-menu', context).click(function () {
        $(this).toggleClass('show');
        if ($(this).siblings('.menu').css('display') !== 'none') {
          $(this).attr('aria-expanded', 'true');
        }
        else {
          $(this).attr('aria-expanded', 'false');
        }
      });

      // Get today's date
      const today = new Date();

      // Update so today is all of the day
      const a = today.setHours(0, 0, 0, 0);
      const todayBegin = parseInt(a, 10);

      // Location for label
      const eventType =
        $('div.node-stanford-event-su-event-type .su-event-type');

      // Location for text
      const eventTextLocation =
        $('div.node-stanford-event-su-event-date-time');

      // Looking for the date on the node page
      const eventDate =
        $('section.event').attr('data-end-date');

      // Build the labels and sentence
      const pastLabel =
        $('<span class="su-event-label-past">' + Drupal.t('Past Event') +
          '<span class="divider">' + Drupal.t('|') + '</span></span>');

      const pastText =
        $('<div class="su-event-text-past">' +
          Drupal.t('This event has passed.') + '</div>');

      // Apply past label and text
      if (eventDate < todayBegin) {
        pastLabel.prependTo(eventType.first());
        pastText.appendTo(eventTextLocation.last());
      }

    })(jQuery, Drupal);
  }
};
