//
//   $(document).ready(function() {
//     // Fonction pour ouvrir la pop-up d'ajout de produit
//     $("#ajouterProduit").click(function() {
//       $("#ajouterProduitModal").modal();
//     });
//
//     // Fonction pour ajouter un produit
//     $("#validerAjoutProduit").click(function() {
//       // Récupération des données du formulaire
//       var nomProduit = $("#nomProduit").val();
//       var descriptionProduit = $("#descriptionProduit").val();
//       var quantiteProduit = $("#quantiteProduit").val();
//       var imageProduit = $("#imageProduit").val();
//
//       // Ajout du produit dans la liste de produits
//       $("#listeProduits").append("<div class='card' style='width: 18rem;'> <img src='" + imageProduit + "' class='card-img-top' alt='...'> <div class='card-body'> <h5 class='card-title'>" + nomProduit + "</h5> <p class='card-text'>" + descriptionProduit + "</p> <p class='card-text'>Quantité disponible: <span class='quantiteProduit'>" + quantiteProduit + "</span></p> <button type='button' class='btn btn-danger' id='supprimerProduit'>Supprimer</button> <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#modifierProduitModal'>Modifier</button> </div> </div>");
//
//       // Fermeture de la pop-up d'ajout de produit
//       $("#ajouterProduitModal").modal("hide");
//     });
//
//     // Fonction pour supprimer un produit
//     $(document).on("click", "#supprimerProduit", function() {
//       // Récupération de la quantité du produit à supprimer
//       var quantiteProduit = $(this).parents(".card").find(".quantiteProduit").text();
//
//       // Mise à jour de la quantité totale de produits
//       var quantiteTotale = parseInt($("#quantiteTotale").text()) - parseInt(quantiteProduit);
//       $("#quantiteTotale").text(quantiteTotale);
//
//       // Suppression de la carte du produit
//       $(this).parents(".card").remove();
//     });
//
//     // Fonction pour ouvrir la pop-up de modification de produit
//     $(document).on("click", "#modifierProduit", function() {
//       $("#modifierProduitModal").modal("hide");
//       $("#modifierProduitModalAdmin").modal();
//     });
//
//     // Fonction pour modifier un produit
//     $(document).on("click", "#validerModifProduit", function() {
//       // Récupération des données du formulaire
//       var nomProduitModif = $("#nomProduitModif").val();
//       var descriptionProduitModif = $("#descriptionProduitModif").val();
//       var quantiteProduitModif = $("#quantiteProduitModif").val();
//       var imageProduitModif = $("#imageProduitModif").val();
//
//       // Modification des données du produit
//       $("#listeProduits .card:last-of-type .card-title").text(nomProduitModif);
//       $("#listeProduits .card:last-of-type .card-text:first-of-type").text(descriptionProduitModif);
// $("#listeProduits .card:last-of-type .card-text:last-of-type .quantiteProduit").text(quantiteProduitModif);
// $("#listeProduits .card:last-of-type img").attr("src", imageProduitModif);
//
// javascript
// Copy code
//   // Fermeture de la pop-up de modification de produit
//   $("#modifierProduitModalAdmin").modal("hide");
// });
//
// // Fonction pour afficher le tableau de valeurs pour l'administrateur
// $("#afficherTableauAdmin").click(function() {
//   $("#tableauAdmin").toggle();
// });
// });
