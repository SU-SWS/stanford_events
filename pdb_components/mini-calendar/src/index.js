import ReactDOM from 'react-dom';
import React, {useState, useEffect} from 'react';
import {Popover} from "@mui/material";
import Calendar from 'react-calendar';
import qs from 'qs';
import NavigationButton from "./components/NavigationButton";
import EventCard from "./components/EventCard";
import './styles.scss';

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
  const [fetchedUrls, setFetchedUrls] = useState([]);
  const [currentDisplay, setCurrentDisplay] = useState('month');

  useEffect(() => {
    const date = new Date();
    fetchMonthEvents(date.getMonth(), date.getFullYear());
  }, []);

  /**
   * Fetch events from the api for the given month and year.
   * @param month
   *   Month of the year.
   * @param year
   *   Four digit year.
   */
  const fetchMonthEvents = (month, year) => {
    const firstDay = new Date(year, month, 1).getTime() / 1000;
    const lastDay = new Date(year, month + 1, 1).getTime() / 1000;

    getEvents('/jsonapi/node/stanford_event', firstDay, lastDay);

    async function getEvents(url, startDate, endDate) {
      const filters = {};
      if (startDate !== undefined) {
        filters.startDate = {
          condition: {
            path: 'su_event_date_time.value',
            operator: '>=',
            value: startDate
          }
        }
      }
      if (endDate !== undefined) {
        filters.endDate = {
          condition: {
            path: 'su_event_date_time.value',
            operator: '<',
            value: endDate
          }
        }
      }

      // Append the query string to the url.
      if ((startDate !== undefined || endDate !== undefined) && url.indexOf('?') === -1) {
        url += '?' + qs.stringify({
          filter: filters,
          sort: 'su_event_date_time.value'
        }, {encodeValuesOnly: true});
      }

      // We've already fetched this url, we don't want to fetch it again.
      if (fetchedUrls.indexOf(url) >= 0) {
        return;
      }
      // Make sure we don't fetch this url again in the future.
      setFetchedUrls(previousFetched => [...previousFetched, url]);

      // Fetch the url data, set the state and follow any paginated urls.
      await fetch(url)
        .then(response => response.json())
        .then(data => {
          // Set the events state after sorting by the start date & time.
          setEvents(events => [...events, ...data.data.map(item => item.attributes)]);

          // Follow the api data to gather all the events possible.
          if (typeof data.links.next === 'object') {
            getEvents(decodeURI(data.links.next.href));
          }
        });
    }
  }

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
   * When the user changes the display to a different month, fetch those events.
   */
  const onActiveStartDateChange = ({action, activeStartDate, value, view}) => {
    if (view === 'month') {
      fetchMonthEvents(activeStartDate.getMonth(), activeStartDate.getFullYear());
    }
  }

  return (
    <div className="events-mini-calender">
      <Calendar
        minDetail="year"
        onViewChange={({view}) => setCurrentDisplay(view)}
        nextLabel={<NavigationButton currentDisplay={currentDisplay}/>}
        next2Label={<NavigationButton currentDisplay={currentDisplay} double/>}
        prevLabel={<NavigationButton currentDisplay={currentDisplay} previous/>}
        prev2Label={<NavigationButton
          currentDisplay={currentDisplay}
          previous
          double
        />}
        navigationLabel={({label}) =>
          <span>
            <span aria-live="polite">{label}</span>
            <span className="visually-hidden">
              Change display.
            </span>
          </span>
        }
        onClickDay={(value, event) => {
          setAnchorEl(event.currentTarget);
          setChosenDate(value.toLocaleDateString())
        }}
        tileDisabled={({date, view}) => {
          return view === 'month' && !getEventsForDate(date).length
        }}
        tileContent={({date, view}) => {
          const currentDate = new Date();
          return (view === 'month' && date.getDate() === currentDate.getDate()) || (view === 'year' && date.getMonth() === currentDate.getMonth()) ?
            <span className="visually-hidden"> (Current)</span> : null
        }}
        onActiveStartDateChange={onActiveStartDateChange}
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
