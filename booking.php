<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Wanderful Travel Booking</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

  * {
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Montserrat', sans-serif;
    background: linear-gradient(135deg,  #38c7b4 0%,  #38c7b4 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
  }

  .card {
    background: #ffffffdd;
    backdrop-filter: blur(12px);
    border-radius: 20px;
    box-shadow: 0 8px 24px rgb(102 166 255 / 0.4);
    max-width: 480px;
    width: 100%;
    padding: 40px 35px;
    position: relative;
    overflow: hidden;
  }

  h1 {
    font-weight: 700;
    font-size: 2.4rem;
    color:  #38c7b4;
    margin-bottom: 30px;
    text-align: center;
    letter-spacing: 1px;
  }

  form {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  label {
    font-weight: 600;
    color: #38c7b4;
    margin-bottom: 6px;
    display: block;
    font-size: 1rem;
  }

  input[type="text"],
  input[type="email"],
  input[type="tel"],
  input[type="date"],
  input[type="number"],
  select,
  textarea {
    padding: 15px 18px;
    font-size: 1rem;
    border-radius: 12px;
    border: 2px solidrgb(46, 169, 152);
    font-family: 'Montserrat', sans-serif;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    outline: none;
    color: #222;
    background: #f7faff;
    resize: vertical;
  }

  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="tel"]:focus,
  input[type="date"]:focus,
  input[type="number"]:focus,
  select:focus,
  textarea:focus {
    border-color: #38c7b4;
    box-shadow: 0 0 10px  #38c7b4;
    background: #fff;
  }

  select {
    appearance: none;
    -webkit-appearance: none;
    background-image:
      linear-gradient(45deg, transparent 50%,  #38c7b4 50%),
      linear-gradient(135deg,  #38c7b4 50%, transparent 50%);
    background-position:
      calc(100% - 20px) calc(1em + 2px),
      calc(100% - 15px) calc(1em + 2px);
    background-size: 8px 8px;
    background-repeat: no-repeat;
    padding-right: 40px;
    cursor: pointer;
  }

  textarea {
    min-height: 90px;
  }

  .error-message {
    color: #e03e3e;
    font-weight: 600;
    font-size: 0.85rem;
    min-height: 20px;
    margin-top: 3px;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .error-message.visible {
    opacity: 1;
  }

  button[type="submit"] {
    background:  #38c7b4;
    color: #fff;
    font-weight: 700;
    font-size: 1.25rem;
    padding: 16px;
    border: none;
    border-radius: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 8px 20px rgb(37 87 167 / 0.6);
  }

  button[type="submit"]:hover,
  button[type="submit"]:focus {
    background:  #38c7b4;
    box-shadow: 0 12px 34px rgb(25 77 151 / 0.8);
    outline: none;
  }

  #formMessage {
    text-align: center;
    font-weight: 700;
    font-size: 1rem;
    margin-bottom: 15px;
    color: #d9534f;
    min-height: 24px;
  }

  @media (max-width: 520px) {
    .card {
      padding: 30px 25px;
      border-radius: 16px;
    }
  }
</style>
</head>
<body>

  <main class="card" role="main" aria-labelledby="formTitle">
    <h1 id="formTitle">Wanderful Travel Booking</h1>
    <form id="bookingForm" action="handle_booking.php" method="POST" novalidate autocomplete="off" aria-describedby="formMessage">
      <div id="formMessage" role="alert" aria-live="assertive"></div>

      <label for="fullName">Full Name *</label>
      <input type="text" id="fullName" name="fullName" placeholder="Your full name" required aria-required="true" aria-describedby="fullNameError" />
      <div id="fullNameError" class="error-message"></div>

      <label for="email">Email Address *</label>
      <input type="email" id="email" name="email" placeholder="you@example.com" required aria-required="true" aria-describedby="emailError" />
      <div id="emailError" class="error-message"></div>

      <label for="phone">Phone Number *</label>
      <input type="tel" id="phone" name="phone" placeholder="+1 555 123 4567" pattern="^\+?[0-9\s\-]{7,15}$" required aria-required="true" aria-describedby="phoneError" />
      <div id="phoneError" class="error-message"></div>

      <label for="destination">Destination *</label>
      <select id="destination" name="destination" required aria-required="true" aria-describedby="destinationError">
        <option value="" disabled selected>Select destination</option>
        <option value="Paris, France">Paris, France</option>
        <option value="Rome, Italy">Rome, Italy</option>
        <option value="Tokyo, Japan">Tokyo, Japan</option>
        <option value="Sydney, Australia">Sydney, Australia</option>
        <option value="Maui, Hawaii">Maui, Hawaii</option>
        <option value="Cape Town, South Africa">Cape Town, South Africa</option>
      </select>
      <div id="destinationError" class="error-message"></div>

      <label for="travelStart">Travel Start Date *</label>
      <input type="date" id="travelStart" name="travelStart" required aria-required="true" aria-describedby="travelStartError" />
      <div id="travelStartError" class="error-message"></div>

      <label for="travelEnd">Travel End Date *</label>
      <input type="date" id="travelEnd" name="travelEnd" required aria-required="true" aria-describedby="travelEndError" />
      <div id="travelEndError" class="error-message"></div>

      <label for="passengers">Number of Passengers *</label>
      <input type="number" id="passengers" name="passengers" min="1" max="20" value="1" required aria-required="true" aria-describedby="passengersError" />
      <div id="passengersError" class="error-message"></div>

      <label for="specialRequests">Special Requests</label>
      <textarea id="specialRequests" name="specialRequests" placeholder="Any special requests?" rows="4" aria-describedby="specialRequestsHelp"></textarea>
      <div id="specialRequestsHelp" style="font-size: 0.85rem; color: #444; margin-top: -12px; margin-bottom: 12px;">(Optional)</div>

      <button type="submit">Book Now</button>
    </form>
  </main>

<script>
  const form = document.getElementById('bookingForm');
  const formMessage = document.getElementById('formMessage');

  function setError(fieldId, message) {
    const errorElem = document.getElementById(fieldId + 'Error');
    errorElem.textContent = message;
    errorElem.classList.add('visible');
    document.getElementById(fieldId).setAttribute('aria-invalid', 'true');
  }

  function clearErrors() {
    ['fullName', 'email', 'phone', 'destination', 'travelStart', 'travelEnd', 'passengers'].forEach(id => {
      const errorElem = document.getElementById(id + 'Error');
      errorElem.textContent = '';
      errorElem.classList.remove('visible');
      document.getElementById(id).removeAttribute('aria-invalid');
    });
    formMessage.textContent = '';
    formMessage.removeAttribute('role');
  }

  function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(email);
}

function validatePhone(phone) {
  const re = /^\+?[0-9\s\-]{7,15}$/;
  return re.test(phone);
}


  form.addEventListener('submit', e => {
    clearErrors();
    let valid = true;

    const fullName = form.fullName.value.trim();
    const email = form.email.value.trim();
    const phone = form.phone.value.trim();
    const destination = form.destination.value;
    const start = form.travelStart.value;
    const end = form.travelEnd.value;
    const passengers = form.passengers.value;

    if (fullName.length < 2) {
      setError('fullName', 'Please enter your full name (min 2 characters).');
      valid = false;
    }
    if (!validateEmail(email)) {
      setError('email', 'Please enter a valid email address.');
      valid = false;
    }
    if (!validatePhone(phone)) {
      setError('phone', 'Please enter a valid phone number.');
      valid = false;
    }
    if (!destination) {
      setError('destination', 'Please select a destination.');
      valid = false;
    }
    if (!start) {
      setError('travelStart', 'Please select a travel start date.');
      valid = false;
    }
    if (!end) {
      setError('travelEnd', 'Please select a travel end date.');
      valid = false;
    }
    if (start && end && start > end) {
      setError('travelEnd', 'Travel end date must be after start date.');
      valid = false;
    }
    const pNum = parseInt(passengers, 10);
    if (isNaN(pNum) || pNum < 1 || pNum > 20) {
      setError('passengers', 'Passengers must be between 1 and 20.');
      valid = false;
    }

    if (!valid) {
      e.preventDefault();
      formMessage.textContent = 'Please fix the errors above before submitting.';
      formMessage.setAttribute('role', 'alert');
    }
  });
</script>

</body>
</html>
