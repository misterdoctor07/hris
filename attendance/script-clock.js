document.addEventListener('DOMContentLoaded', () =>
  requestAnimationFrame(updateTime)
)

function updateTime() {
  const hour = moment().hour();
  const ampm = hour >= 12 ? 'PM' : 'AM';
  document.documentElement.style.setProperty('--timer-day', "'" + moment().format("dddd") + "'");
  document.documentElement.style.setProperty('--timer-hours', "'" + moment().format("hh") + "'");
  
  document.documentElement.style.setProperty('--timer-minutes', "'" + moment().format("mm") + "'");
  document.documentElement.style.setProperty('--timer-seconds', "'" + moment().format("ss") + "'");
  document.documentElement.style.setProperty('--timer-ampm', "'" + ampm + "'");
  requestAnimationFrame(updateTime);
}