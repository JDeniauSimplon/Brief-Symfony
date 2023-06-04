/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

document.addEventListener('DOMContentLoaded', function() {
    var editButtons = document.querySelectorAll('.edit');

    editButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            var line = button.closest('.informationsLine');
            var information = line.querySelector('.actualInformation');
            information.setAttribute('contenteditable', 'true');
            information.focus();
        });
    });

    var actualInformations = document.querySelectorAll('.actualInformation');

    actualInformations.forEach(function(information) {
        information.addEventListener('blur', function() {
            var line = information.closest('.informationsLine');
            information.setAttribute('contenteditable', 'false');
            // Envoyez les modifications au serveur ici
        });
    });
});

// Récupérez tous les éléments de classe "edit"
const editButtons = document.getElementsByClassName("edit");

// Parcourez tous les boutons "edit" et ajoutez un gestionnaire d'événement pour chaque clic
Array.from(editButtons).forEach((editButton) => {
    editButton.addEventListener("click", () => {
        const parentLine = editButton.parentNode;
        const actualInformation = parentLine.querySelector(".actualInformation");
        const originalValue = actualInformation.innerText;
        actualInformation.dataset.originalValue = originalValue;
        actualInformation.contentEditable = true;
        editButton.style.display = "none";
        const cancelButton = parentLine.querySelector(".cancel");
        cancelButton.style.display = "inline-block";
        const validateButton = parentLine.querySelector(".validate");
        validateButton.style.display = "inline-block";
    });
});

// Récupérez tous les éléments de classe "cancel"
const cancelButtons = document.getElementsByClassName("cancel");

// Parcourez tous les boutons "cancel" et ajoutez un gestionnaire d'événement pour chaque clic
Array.from(cancelButtons).forEach((cancelButton) => {
    cancelButton.addEventListener("click", () => {
        const parentLine = cancelButton.parentNode;
        const actualInformation = parentLine.querySelector(".actualInformation");
        const originalValue = actualInformation.dataset.originalValue;
        actualInformation.innerText = originalValue;
        cancelButton.style.display = "none";
        const editButton = parentLine.querySelector(".edit");
        editButton.style.display = "inline-block";
        const validateButton = parentLine.querySelector(".validate");
        validateButton.style.display = "none";
        actualInformation.contentEditable = false;
    });
});

// Récupérez tous les éléments de classe "validate"
const validateButtons = document.getElementsByClassName("validate");

// Parcourez tous les boutons "validate" et ajoutez un gestionnaire d'événement pour chaque clic
Array.from(validateButtons).forEach((validateButton) => {
    validateButton.addEventListener("click", () => {
        const parentLine = validateButton.parentNode;
        const actualInformation = parentLine.querySelector(".actualInformation");
        const editButton = parentLine.querySelector(".edit");
        const cancelButton = parentLine.querySelector(".cancel");

        // Désactiver la modification en supprimant l'attribut contentEditable
        actualInformation.contentEditable = false;

        // Masquer les boutons "Valider" et "Annuler"
        validateButton.style.display = "none";
        cancelButton.style.display = "none";

        // Réactiver le bouton "Modifier" une fois que la modification est validée
        editButton.disabled = false;
    });
});

window.enableEdit = function(editButton) {
    const parentLine = editButton.parentNode;
    const actualInformation = parentLine.querySelector('.actualInformation');
    const validateButton = parentLine.querySelector('.validate');
    const cancelButton = parentLine.querySelector('.cancel');

    // active le champ d'entrée
    actualInformation.disabled = false;
    actualInformation.focus();
    editButton.style.display = 'none';
    validateButton.style.display = 'block';
    cancelButton.style.display = 'block';
}


function disableEdit(cancelButton) {
    const parentLine = cancelButton.parentNode;
    const actualInformation = parentLine.querySelector('.actualInformation');
    const editButton = parentLine.querySelector('.edit');
    const validateButton = parentLine.querySelector('.validate');

    // désactive le champ d'entrée
    actualInformation.disabled = true;
    editButton.style.display = 'block';
    validateButton.style.display = 'none';
    cancelButton.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const cancelButton = document.querySelectorAll('.cancel');
    cancelButton.forEach((button) => {
        button.addEventListener('click', () => {
            const parentLine = button.parentNode;
            const actualInformation = parentLine.querySelector('.actualInformation');
            const originalValue = actualInformation.dataset.originalValue;
            actualInformation.textContent = originalValue;
            disableEdit(button);
        });
    });

    const updateForm = document.getElementById('updateForm');
    updateForm.addEventListener('submit', (e) => {
        const informationsLines = updateForm.querySelectorAll('.informationsLine');
        informationsLines.forEach((line) => {
            const actualInformation = line.querySelector('.actualInformation');
            const inputName = actualInformation.getAttribute('name');
            const inputValue = actualInformation.textContent.trim();
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', inputName);
            hiddenInput.setAttribute('value', inputValue);
            updateForm.appendChild(hiddenInput);
        });
    });
});
