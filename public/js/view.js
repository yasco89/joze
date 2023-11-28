
// Pour afficher le calendrier des reservation

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        events: [
            {% for reservation in reservations %}
                {
                    title: '{{ reservation.name }}',
                    start: '{{ reservation.date.format('Y-m-d') }}',
                    end: '{{ reservation.dateRetour.format('Y-m-d') }}',
                },
            {% endfor %}
        ],
    });

    calendar.render();
});

// Liste des dates réservées depuis le contrôleur Symfony
// Vous devrez probablement adapter ce code pour le faire fonctionner avec vos données
var reservedDates = [
  {% for reservation in reservations %}
    '{{ reservation.date|date('Y-m-d') }}',
  {% endfor %}
];

// Fonction pour vérifier si une date est réservée
function isReserved(date) {
  return reservedDates.includes(date);
}

// Écouteurs d'événements pour les champs de date
document.getElementById("dateDebut").addEventListener("change", function() {
  if (isReserved(this.value)) {
    alert("Cette date de début est déjà réservée.");
    this.value = "";  // Effacer la date
  }
});

document.getElementById("dateRetour").addEventListener("change", function() {
  if (isReserved(this.value)) {
    alert("Cette date de retour est déjà réservée.");
    this.value = "";  // Effacer la date
  }
});
