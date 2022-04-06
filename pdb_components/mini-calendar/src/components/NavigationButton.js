import React from "react";

const NavigationButton = ({currentDisplay, previous, double}) => {
  const availableDisplays = ['month', 'year', 'decade'];
  let icon = '›';
  if (previous) {
    icon = double ? '«' : '‹';
  }
  else {
    icon = double ? '»' : '›';
  }

  return (
    <div>
      <span className="visually-hidden">
        {previous ? 'Previous' : 'Next'} {double ? availableDisplays[availableDisplays.indexOf(currentDisplay) + 1] : currentDisplay}
      </span>
      <span aria-hidden={true}>
        {icon}
      </span>
    </div>
  )
}

export default NavigationButton;
