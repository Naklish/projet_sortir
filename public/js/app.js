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
    action.innerHTML = "<a href=\"#\">Afficher</a>";

    // Si l'utilisateur connecté est l'organisateur
    if (isOrg(userId, orgId)) {
        if (stateId !== 1) {
            action.innerHTML = "<a href=\"#\">Afficher</a> - <a href=\"#\">Annuler</a>";
        } else if (stateId === 1) {
            action.innerHTML = "<a href=\"#\">Modifier</a> - <a href=\"#\">Publier</a>";
        }
        // Si l'utilisateur connecté n'est pas l'organisateur
    } else {
        if (isRegistered(userId, outingId)) {
            if (stateId === 2) {
                action.innerHTML = "<a href=\"#\">Afficher</a> - <a href=\"#\">Se désister</a>";
            }
        } else {
                if (stateId === 2) {
                    action.innerHTML = "<a href=\"#\">Afficher</a> - <a href=\"#\">S'inscrire</a>";
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