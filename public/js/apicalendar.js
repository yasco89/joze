
document.addEventListener("DOMContentLoaded", function () {
    const reserverBtn = document.getElementById("reserverBtn");

    reserverBtn.addEventListener("click", function () {
        const telephone = document.getElementById("telephone").value;
        const dateDebut = document.getElementById("dateDebut").value;
        const dateRetour = document.getElementById("dateRetour").value;

        gapi.load("client:auth2", function () {

        return gapi.client.init({
                apiKey: "AIzaSyCszSMZyENmHqAIODPPNWHH08kE1f3s2FQ",
                clientId: "314528950101-gluk573tc715prij910t1682puhis144.apps.googleusercontent.com",
                discoveryDocs: ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"],
                scope: "https://www.googleapis.com/auth/calendar.events"
            }).then(function () {

               let result= gapi.client.calendar.events.insert({
                    calendarId: "primary",
                    resource: {
                        summary: "Réservation de produit",
                        description: `Téléphone: ${telephone}\nDate début: ${dateDebut}\nDate retour: ${dateRetour}`,
                        start: {
                            dateTime: `${dateDebut}T00:00:00+02:00`,
                            timeZone: "Europe/Paris"
                        },
                        end: {
                            dateTime: `${dateRetour}T23:59:59+02:00`,
                            timeZone: "Europe/Paris"
                        }
                    }
                });
                console.log("mon resultat",result);
                return result;
            }).then(function (response) {
                console.log("Event created: " + response.result.htmlLink);
                alert("Événement créé avec succès !");
            }).catch(function (error) {
                console.error("Error creating event: " + JSON.stringify(error));
                alert("Erreur lors de la création de l'événement. Veuillez consulter la console pour plus de détails.");
            });
        });
    });
});
