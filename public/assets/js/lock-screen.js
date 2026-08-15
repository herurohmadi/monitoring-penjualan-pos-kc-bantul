// Update jam setiap detik
function updateClock() {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, "0");
  const minutes = String(now.getMinutes()).padStart(2, "0");
  const seconds = String(now.getSeconds()).padStart(2, "0");
  const timeString = `${hours}:${minutes}:${seconds}`;
  document.getElementById("clock").textContent = timeString;
}

// Tanggal format Indonesia
function updateDate() {
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  const today = new Date();
  const formattedDate = today.toLocaleDateString("id-ID", options);
  document.getElementById("current-date").textContent = formattedDate;
}

setInterval(updateClock, 1000);
updateClock();
updateDate();
