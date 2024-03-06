var confirmButton = document.getElementById('confirme');

// Ajoutez un écouteur d'événements pour le clic sur le bouton
confirmButton.addEventListener('click', function() {
    // Affichez une boîte de dialogue de confirmation
    if (confirm("Voulez-vous vraiment effectuer cette action?")) {
        // Si l'utilisateur clique sur "OK", vous pouvez exécuter une action
        // Par exemple, rediriger vers une autre page ou envoyer une requête AJAX
        alert("Action confirmée!");
        // Insérez ici le code pour l'action à effectuer après la confirmation
    } else {
        // Si l'utilisateur clique sur "Annuler", vous pouvez ne rien faire ou afficher un message supplémentaire
        alert("Action annulée!");
    }
});