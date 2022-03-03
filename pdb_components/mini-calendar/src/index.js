import React, {useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
import Calendar from 'react-calendar';
import {Popover} from "@mui/material";
import Moment from 'moment';
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

  useEffect(() => {
    getEvents();

    async function getEvents(url = '/jsonapi/node/stanford_event') {
      await fetch(url)
        .then(response => response.json())
        .then(data => {
          setEvents(events => [...events, ...data.data.map(item => item.attributes)].sort((a, b) => {
            const aStart = new Date(a.su_event_date_time.value);
            const bStart = new Date(b.su_event_date_time.value)
            return aStart <= bStart ? -1 : 1;
          }));

          // Follow the api data to gather all the events possible.
          if (typeof data.links.next === 'object') {
            getEvents(data.links.next.href);
          }
        });
    }
  }, []);

  const getEventsForDate = (date) => {
    const givenDate = new Date(date);
    return events.filter((event) => {
      const eventDate = new Date(event.su_event_date_time.value);
      return givenDate.toLocaleDateString() === eventDate.toLocaleDateString();
    });
  }

  const getMinDate = () => {
    if (events.length === 0) {
      return new Date();
    }
    return new Date(events[0].su_event_date_time.value);
  }

  const getMaxDate = () => {
    if (events.length === 0) {
      return new Date();
    }
    return new Date(events[events.length - 1].su_event_date_time.value);
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
        <button className="far fa-window-close close-button" onClick={() => setAnchorEl(null)}>
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

const EventCard = ({title, path, su_event_date_time}) => {

  const startTime = Moment(su_event_date_time.value).format('h:mm A');
  const endTime = Moment(su_event_date_time.end_value).format('h:mm A');
  const duration = startTime === '12:00 AM' && endTime === '11:59 PM' ? 'All Day' : `${startTime} to ${endTime}`

  return (
    <li className="popover-item">
      <a href={path.alias}>{title}</a>
      <div>{duration}</div>
    </li>
  )
}
