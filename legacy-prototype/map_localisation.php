<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Définir l'adresse via la carte</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE_API&libraries=places"></script>
  <style>
    #map {
      width: 100%;
      height: 80vh;
      margin-bottom: 1rem;
    }
  </style>
</head>
<body>
  <h2>Choisissez votre emplacement</h2>
  <div id="map"></div>
  <button onclick="confirmerAdresse()">Confirmer l’adresse</button>

  <script>
    let map, marker, geocoder;
    function initMap() {
      geocoder = new google.maps.Geocoder();
      navigator.geolocation.getCurrentPosition(position => {
        const pos = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        map = new google.maps.Map(document.getElementById("map"), {
          center: pos,
          zoom: 15
        });
        marker = new google.maps.Marker({
          position: pos,
          map: map,
          draggable: true
        });
      }, () => {
        alert("Impossible d’obtenir la géolocalisation.");
      });
    }

    function confirmerAdresse() {
      const pos = marker.getPosition();
      geocoder.geocode({ location: pos }, (results, status) => {
        if (status === "OK" && results[0]) {
          const adresse = results[0].formatted_address;
          // Retour à la page précédente avec l'adresse
          window.opener.document.querySelector('input[name="adresse"]').value = adresse;
          window.close();
        } else {
          alert("Adresse introuvable.");
        }
      });
    }

    window.onload = initMap;
  </script>
</body>
</html>
