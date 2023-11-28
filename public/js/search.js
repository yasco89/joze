
// creation d'une fonction alert pour les cookies
document.addEventListener('DOMContentLoaded', function () {
    // Vérifier si la boîte de dialogue a déjà été affichée
    if (document.cookie.indexOf('alerteCookiesAffichee=true') === -1) {
        // Si non, afficher la boîte de dialogue modale
        afficherAlerte();
    }
});

function afficherAlerte() {
    // Afficher la boîte de dialogue modale
    var modal = document.getElementById('myModal');
    var overlay = document.getElementById('overlay');

    modal.style.display = 'block';
    overlay.style.display = 'block';

    // Fermer la boîte de dialogue modale lorsque le bouton Fermer est cliqué
    var closeBtn = document.getElementById('closeBtn');
    closeBtn.onclick = function () {
        modal.style.display = 'none';
        overlay.style.display = 'none';
    };

    // Enregistrer l'état dans un cookie qui expire après 365 jours
    var expirationDate = new Date();
    expirationDate.setFullYear(expirationDate.getFullYear() + 1);
    document.cookie = 'alerteCookiesAffichee=true; expires=' + expirationDate.toUTCString() + '; path=/';
}

function accepterCookies() {
    alert('Vous avez accepté les cookies!');
    fermerAlerte();
}

function refuserCookies() {
    alert('Vous avez refusé les cookies!');
    fermerAlerte();
}

function fermerAlerte() {
    var modal = document.getElementById('myModal');
    var overlay = document.getElementById('overlay');
    modal.style.display = 'none';
    overlay.style.display = 'none';
}
function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}


// // La fonction qui permet de trouver les element famille dans la liste des equipement
// function search() {
//   var input, filter, table, tr, td, i, txtValue;
//   input = document.getElementById("searchInput");
//   filter = input.value.toUpperCase();
//   table = document.getElementById("myTable");
//   tr = table.getElementsByTagName("tr");
//
//   for (i = 0; i < tr.length; i++) {
//     td = tr[i].getElementsByTagName("td")[0];
//     if (td) {
//       txtValue = td.textContent || td.innerText;
//       if (txtValue.toUpperCase().indexOf(filter) > -1) {
//         tr[i].style.display = "";
//       } else {
//         tr[i].style.display = "none";
//       }
//     }
//   }
// }

// Cette function permet de filtrer la famille du produit dans la page d'Accueil
function productsearch() {
  const input = document.getElementById('searchInput');
  const filter = input.value.toUpperCase();
  const cardTitles = document.querySelectorAll('.card-title[data-search]');

  for (let i = 0; i < cardTitles.length; i++) {
    const name = cardTitles[i].getAttribute('data-search').toUpperCase();
    const card = cardTitles[i].closest('.product');
    if (name.indexOf(filter) > -1) {
      card.style.display = '';
    } else {
      card.style.display = 'none';
    }
  }
}







// Pour filtrer les prix
function filterByPrice() {
  var selectedValue = document.getElementById('filterByPrice').value; // Prix sélectionné

  // Parcourez tous les éléments du produits
  var produits = document.getElementsByClassName('product');
  for (var i = 0; i < produits.length; i++) {
    var produit = produits[i];
    var prix = parseInt(produit.querySelector('.price').getAttribute('data-search')); // prix du produits

    // Affichez ou masquez le produits en fonction de la sélection de l'utilisateur
    if (selectedValue === 'all' || (selectedValue === '0-10' && prix >= 0 && prix <= 10) || (selectedValue === '11-20' && prix >= 11 && prix <= 20) || (selectedValue === '21-30' && prix >= 21 && prix <= 30)) {
      produit.style.display = 'block'; // Afficher
    } else {
      produit.style.display = 'none'; // Masquer
    }
  }
}


document.addEventListener("DOMContentLoaded", function () {
  const reserverBtn = document.getElementById("reserverBtn");

  reserverBtn.addEventListener("click", function () {
    gapi.load("client:auth2", function () {
      gapi.client.init({
        apiKey: "114219ec775a08efb75f53e0440dc6556704d3c3",
        clientId: "105266356296369731347",
        discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"],
        scope: "https://www.googleapis.com/auth/calendar.events"
      }).then(function () {
        return gapi.client.calendar.events.insert({
          calendarId: "primary",
          resource: {
            summary: "Réservation de matériel",
            description: "Réserver le produit date de début",
            start: {
              dateTime: "2023-08-07T10:00:00",
              timeZone: "Europe/Paris"
            },
            end: {
              dateTime: "2033-08-07T11:00:00",
              timeZone: "Europe/Paris"
            }
          }
        });
      }).then(function (response) {
        console.log("Event created: " + response.result.htmlLink);
      }).catch(function (error) {
        console.error("Error creating event: " + error);
      });
    });
  });
});
