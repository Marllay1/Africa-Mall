<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Confirmation du code</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
body {
  font-family: 'Inter', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #FFF7ED; /* Correspond à Tailwind: bg-orange-50 */
}
    .container {
      max-width: 400px;
      margin: 80px auto 30px auto;
      padding: 30px 20px;
    }
    .back-button {
      position: absolute;
      top: 20px;
      left: 20px;
      text-decoration: none;
      color: orange;
      font-weight: bold;
      font-size: 16px;
    }
    h3 {
      text-align: center;
      font-size: 18px;
      margin-bottom: 25px;
      color: #333;
    }
    .code-inputs {
  display: flex;
  justify-content: center;
  gap: 8px; /* Réduit l'espacement entre les cases */
  margin-bottom: 20px;
}
.code-inputs input {
  width: 40px; /* Optionnellement rétréci */
  height: 45px;
  font-size: 24px;
  text-align: center;
  border: 1px solid #ccc;
  border-radius: 8px;
}
    .code-actions {
      text-align: center;
      font-size: 14px;
      margin-bottom: 15px;
    }
    .code-actions span {
      display: block;
      color: blue;
      cursor: pointer;
      margin-top: 5px;
    }
    .error-message {
      color: red;
      font-size: 14px;
      text-align: center;
      margin-bottom: 10px;
      display: none;
    }
    .submit-btn {
  width: 200px; /* Largeur réduite */
  padding: 12px;
  font-size: 16px;
  background-color: #EA580C; /* Correspond à Tailwind: bg-orange-600 */
  color: #fff;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  margin: 10px auto 0 auto; /* Centrage horizontal */
  display: block;
}
  .submit-btn:hover {
  background-color: #EA580C; /* Correspond à Tailwind: bg-orange-600 */
}
    .login-msg {
      margin-top: 20px;
      text-align: center;
      font-size: 14px;
    }
    .login-msg a {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>

<a href="login.php" class="back-button">←</a>

<div class="container">
  <h3>Veuillez saisir le code reçu par SMS</h3>



  <div class="code-inputs">
    <input type="text" maxlength="1" oninput="moveToNext(this, 0)">
    <input type="text" maxlength="1" oninput="moveToNext(this, 1)">
    <input type="text" maxlength="1" oninput="moveToNext(this, 2)">
    <input type="text" maxlength="1" oninput="moveToNext(this, 3)">
    <input type="text" maxlength="1" oninput="moveToNext(this, 4)">
    <input type="text" maxlength="1" oninput="moveToNext(this, 5)">
  </div>

  <div class="code-actions">
    <p>Pas encore reçu ?</p>
    <span onclick="resendSMS()">Renvoyer un SMS</span>
    <span onclick="receiveCall()">Recevoir un appel</span>
  </div>

  <div class="error-message" id="error-msg">Le code saisi est incorrect.</div>


  <form action="welcome.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="name" value="<?php echo $name; ?>">
    <input type="hidden" name="email" value="<?php echo $email; ?>">
    <input type="hidden" name="phone" value="<?php echo $phone; ?>">
    <input type="hidden" name="photo" value="<?php echo $photo; ?>">
    <button class="submit-btn" onclick="submitCode()">Envoyer</button>




<script>
  function moveToNext(elem, index) {
    const inputs = document.querySelectorAll('.code-inputs input');
    if (elem.value.length === 1 && index < inputs.length - 1) {
      inputs[index + 1].focus();
    }
  }

  function submitCode() {
    const inputs = document.querySelectorAll('.code-inputs input');
    let code = '';
    inputs.forEach(input => code += input.value);

    fetch('process-code.php1', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: 'code=' + code
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = data.redirect;
      } else {
        document.getElementById('error-msg').style.display = "block";
        document.getElementById('error-msg').textContent = data.message;
      }
    });
  }

  function resendSMS() {
    alert("Un nouveau code vous a été envoyé par SMS !");
  }

  function receiveCall() {
    alert("Un appel est en cours pour vous transmettre le code !");
  }
</script>

</body>
</html>