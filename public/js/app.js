/************************* GESTION AFFICHAGE TABLEAU *************************/

/**
 * Appelle la fonction isRegistered() pour savoir si le user connecté est inscrit
 * Si c'est le cas, on met une croix dans la case "Inscrit"
 * @param userId
 * @param outingId
 */
let countId = 0;

function checkRegister(userId, outingId) {
    const balise = document.getElementById("registerColumn");
    balise.id = "registerColumn" + countId;

    if (isRegistered(userId, outingId)) {
        balise.innerHTML = "X";
    }
    countId++;
}

let countActions = 0;

/**
 * Rempli la colonne "Action" selon les états des sorties,
 * si l'organisateur est incrit, s'il est l'organisateur...
 * @param userId
 * @param orgId
 * @param stateId
 * @param outingId
 */
function fillActionColumn(userId, orgId, stateId, outingId) {
    const action = document.getElementById("actionColumn");
    action.id = "actionColumn" + countActions;

    const url = "http://localhost:8888/projet_sortir/public/";
    const urlOuting = url + "outing/";
    const urlUser = url + "user/";
    action.innerHTML = '<a href="' + urlOuting + "details/" + outingId + '">Afficher</a>';

    // Si l'utilisateur connecté est l'organisateur
    if (isOrg(userId, orgId)) {
        if (stateId !== 1 && stateId !== 6) {
            action.innerHTML = '<a href="' + urlOuting + "details/" + outingId + '">Afficher</a> - <a href="' + urlOuting + "cancel/" + outingId + '">Annuler</a>';
        } else if (stateId === 1) {
            action.innerHTML = '<a href="' + urlOuting + "modify/" + outingId + '">Modifier</a> - <a href="' + urlOuting + "publish/" + outingId + '">Publier</a>';
        }
        // Si l'utilisateur connecté n'est pas l'organisateur
    } else {
        if (isRegistered(userId, outingId)) {
            if (stateId === 2) {
                action.innerHTML = '<a href="' + urlOuting + "details/" + outingId + '">Afficher</a> - <a href="' + urlUser + "unregister/" + outingId + '">Se désister</a>';
            }
        } else {
            if (stateId === 2) {
                action.innerHTML = '<a href="' + urlOuting + "details/" + outingId + '">Afficher</a> - <a href="' + urlUser + "register/" + outingId + '">S\'inscrire</a>';
            }
        }
    }
    countActions++;
}

/**
 * Vérifie si l'utilisateur connecté est organisateur de la sortie
 */
function isOrg(userId, orgId) {
    return userId === orgId;
}


/**
 * Vérifie si l'utilisateur connecté est inscrit à la sortie
 * @param userId
 * @param outingId
 * @returns {boolean}
 */
function isRegistered(userId, outingId) {
    // On récupère le tableau contenant les id des participants de toutes les sorties
    const arrayRegUsers = document.getElementById('arrayRegUsers').value;
    const arrRegUsers = JSON.parse(arrayRegUsers);

    // On crée un tableau contenant les id des participants
    // et un autre content les id des sorties
    const registeredUser = arrRegUsers.map(a => a.id);
    const allOutingsIds = arrRegUsers.map(b => b.oid)

    // Test si l'id de la sortie actuellement parcourue dans le tableau Twig
    // se trouve dans le tableau allOutingsIds. Si oui, on l'ajoute à un nouveau tableau
    const outingRegisteredUser = [];
    for (let i = 0; i < allOutingsIds.length; i++) {
        if (allOutingsIds[i] === outingId) {
            outingRegisteredUser.push(registeredUser[i])
        }
    }
    console.log(outingRegisteredUser)
    // Si l'id de l'utilisateur connecté se trouve dans le tableau outingRegisteredUser, on renvoie true
    return outingRegisteredUser.includes(userId);
}


/************************* TRAITEMENT FORMULAIRES *************************/
let countCampus = 0;

function campusForm(outId) {
    arrayLine = document.getElementById("tab-line");
    arrayLine.id = "tab-line" + countCampus;
    const valueCampus = document.getElementById("campus").value;
    // console.log("formulaire id : " + valueCampus);
    // console.log("id sortie parcourue : " + outId);

    if (valueCampus !== "") {
        if (valueCampus != outId) {
            arrayLine.remove();
        } else {
            // arrayLine.style.display = "block";
            console.log("coucou");
        }
    }
    countCampus++;
}