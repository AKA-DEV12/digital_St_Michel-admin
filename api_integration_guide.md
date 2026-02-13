# 📱 Guide d'Intégration API - Scan de Tickets

Ce document regroupe toutes les informations nécessaires pour intégrer les fonctionnalités de scan de tickets dans l'application **Flutter**.

## 🌍 Configuration Globale

- **Base URL** : `https://admin.saint-michel-archange.com/api`
- **Format des données** : JSON
- **Authentification** : Laravel Sanctum (Bearer Token)

---

## 🔑 Authentification

### 1. Connexion de l'Agent
Permet à un agent de terrain de se connecter pour obtenir son jeton d'accès.

- **URL** : `/agent/login`
- **Méthode** : `POST`
- **Body** :
```json
{
    "email": "agent@example.com",
    "password": "votre_mot_de_passe"
}
```
- **Réponse (Succès)** :
```json
{
    "status": "success",
    "token": "1|AbCdEfG...",
    "user": { "id": 1, "name": "Nom Agent", ... }
}
```

---

## 🎟️ Gestion des Tickets

### 2. Vérification / Scan d'un Ticket
Cette route permet de valider la présence d'un participant.

- **URL** : `/scan/{id}`
- **Méthode** : `POST` ou `GET`
- **Headers** : `Authorization: Bearer {token}`
- **Paramètre** : `{id}` est l'identifiant numérique extrait du QR Code.

- **Réponse (Succès - Nouveau Scan)** :
```json
{
    "status": "success",
    "message": "Vérification réussie pour : Nom du Participant",
    "data": {
        "participant": "Nom",
        "activity": "Titre Activité",
        "scan_time": "13/02/2026 à 10:15"
    }
}
```

- **Réponse (Attention - Déjà Scanné)** :
```json
{
    "status": "warning",
    "message": "Ce QR Code a déjà été scanné le 13/02/2026 à 09:00.",
    "data": { ... }
}
```

- **Réponse (Erreur - Inconnu)** :
```json
{
    "status": "error",
    "message": "Inscription introuvable. Ce ticket n'est pas connu."
}
```

### 3. Historique des Scans par Agent
Permet à l'agent de consulter la liste des personnes qu'il a déjà validées.

- **URL** : `/agents/{id}/scanned`
- **Méthode** : `GET`
- **Headers** : `Authorization: Bearer {token}`

---

## 🛠️ Instructions pour Flutter (Scanner QR)

Le package `mobile_scanner` est déjà installé dans l'application Flutter (voir `pubspec.yaml`). Suivez ces règles de flux utilisateur pour son utilisation :

1.  **Détection** : Lorsque le scanner détecte un QR Code, celui-ci ne doit pas déclencher l'appel API automatiquement.
2.  **Interface** : Affichez un bouton **"Vérifier le ticket"** qui apparaît après la lecture du code.
3.  **Action** : Ce bouton doit récupérer l'identifiant extrait par le scanner et appeler la route : `https://admin.saint-michel-archange.com/api/scan/{id}`.
4.  **Feedback** : Affichez le résultat de l'API (Succès, Alerte ou Erreur) dans une modale de confirmation.

---

> [!IMPORTANT]
> Assurez-vous de stocker le `token` de manière sécurisée (Secure Storage) et de l'inclure dans le header de chaque requête authentifiée.
