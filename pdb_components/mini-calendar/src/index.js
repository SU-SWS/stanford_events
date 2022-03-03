import React, {useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
import Calendar from 'react-calendar';
import {Popover} from "@mui/material";
import Moment from 'moment';
import './styles.scss';
import qs from "qs";

ReactDOM.render(
  <React.StrictMode>
    <App/>
  </React.StrictMode>,
  document.getElementById('event-mini-cal')
);

function App() {
  const [anchorEl, setAnchorEl] = useState(null);
  const [chosenDate, setChosenDate] = useState('');
  const [events, setEvents] = useState([]);

  useEffect(() => {
    getThisMonthEvents('/jsonapi/node/stanford_event');

    // Grab the current month of events from the API first to display contents
    // as early as possible. Once those are done, then we'll grab the remaining
    // events from the API.
    async function getThisMonthEvents(url) {
      var date = new Date();

      // Use 6 days before the first of the month & after the end of the month
      // so that if the 1st is on a Saturday, we'll load events for the entire
      // month display, not just the current month.
      var firstDay = new Date(date.getFullYear(), date.getMonth(), -6).getTime() / 1000;
      var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 6).getTime() / 1000;

      getEvents(url, firstDay, lastDay, '>=', '<=').then(() => {
        getEvents(url, lastDay).then(() => getEvents(url, null, firstDay));
      });
    }

    /**
     * Fetch from the JsonAPI and set the state with the response data.
     *
     * @param url
     *   The API url.
     * @param startDate
     *   Optional start date timestamp filter.
     * @param endDate
     *   Optional end date timestamp filter.
     * @param startDateOperator
     *   Start filter operator.
     * @param endDateOperator
     *   End filter operator.
     *
     * @returns {Promise<void>}
     */
    async function getEvents(url, startDate, endDate, startDateOperator = '>', endDateOperator = '<') {
      const filters = {};
      if (startDate !== undefined) {
        filters.startDate = {
          condition: {
            path: 'su_event_date_time.value',
            operator: startDateOperator,
            value: startDate
          }
        }
      }
      if (endDate !== undefined) {
        filters.endDate = {
          condition: {
            path: 'su_event_date_time.value',
            operator: endDateOperator,
            value: endDate
          }
        }
      }

      // Append the query string to the url.
      if (startDate !== undefined || endDate !== undefined && url.indexOf('?') === -1) {
        url += '?' + qs.stringify({filter: filters}, {encodeValuesOnly: true});
      }

      // Fetch the url data, set the state and follow any paginated urls.
      await fetch(url)
        .then(response => response.json())
        .then(data => {
          // Set the events state after sorting by the start date & time.
          setEvents(events => [...events, ...data.data.map(item => item.attributes)].sort((a, b) => {
            const aStart = new Date(a.su_event_date_time.value);
            const bStart = new Date(b.su_event_date_time.value)
            return aStart <= bStart ? -1 : 1;
          }));

          // Follow the api data to gather all the events possible.
          if (typeof data.links.next === 'object') {
            getEvents(decodeURI(data.links.next.href));
          }
        });
    }
  }, []);

  /**
   * Grab all events that occur on the given date string.
   *
   * @param date
   *   Date string.
   *
   * @returns array
   *   Array of events.
   */
  const getEventsForDate = (date) => {
    const givenDate = new Date(date);
    return events.filter((event) => {
      const eventDate = new Date(event.su_event_date_time.value);
      // Compare only the date, not the time.
      return givenDate.toLocaleDateString() === eventDate.toLocaleDateString();
    });
  }

  /**
   * Get the earliest available event date.
   *
   * @returns {Date}
   */
  const getMinDate = () => {
    return events.length === 0 ? new Date() : new Date(events[0].su_event_date_time.value);
  }

  /**
   * Get the last available event date.
   * @returns {Date}
   */
  const getMaxDate = () => {
    return events.length === 0 ? new Date() : new Date(events[events.length - 1].su_event_date_time.value);
  }

  return (
    <div className="events-mini-calender">
      <Calendar
        nextAriaLabel="Next Month"
        next2AriaLabel="Next Year"
        prevAriaLabel="Previous Month"
        prev2AriaLabel="Previous Year"
        minDate={getMinDate()}
        maxDate={getMaxDate()}
        onClickDay={(value, event) => {
          setAnchorEl(event.currentTarget);
          setChosenDate(value.toLocaleDateString())
        }}
        tileDisabled={({activeStartDate, date, view}) => {
          return view === 'month' && !getEventsForDate(date).length
        }}
      />
      <Popover
        className="calender-popover"
        open={Boolean(anchorEl)}
        anchorEl={anchorEl}
        onClose={() => setAnchorEl(null)}
        anchorOrigin={{
          vertical: 'bottom',
          horizontal: 'left',
        }}
      >
        <button
          className="far fa-window-close close-button"
          onClick={() => setAnchorEl(null)}
        >
          <span className="visually-hidden">Close</span>
        </button>

        <ul className="popover-list">
          {getEventsForDate(chosenDate).map(event =>
            <EventCard key={event.drupal_internal__nid} {...event}/>
          )}
        </ul>
      </Popover>
    </div>
  );
}

/**
 * Individual event card component.
 */
const EventCard = ({title, path, su_event_date_time}) => {

  const startTime = Moment(su_event_date_time.value).format('h:mm A');
  const endTime = Moment(su_event_date_time.end_value).format('h:mm A');
  // Smart date stores the values that are all day as midnight and 11:59 PM, so
  // check for those values and provide a string with the duration.
  const duration = startTime === '12:00 AM' && endTime === '11:59 PM' ? 'All Day' : `${startTime} to ${endTime}`

  return (
    <li className="popover-item">
      <a href={path.alias}>{title}</a>
      <div className="duration">{duration}</div>
    </li>
  )
}
