
import os
import time
import random
import mysql.connector
from datetime import datetime

# Connexion à la base de données
DB_CONFIG = {
    "host": os.getenv("DB_HOST", "localhost"),
    "user": os.getenv("DB_USER", "root"),
    "password": os.getenv("DB_PASSWORD", ""),
    "database": os.getenv("DB_NAME", "olympiades"),
}

# Attentes moyennes de référence et capacité estimée.
# À ajuster selon ton parc.
ATTRACTIONS = {
    "La Falaise": {"base": 15, "max": 90},
    "Le Trou Portable": {"base": 10, "max": 60},
    "Le Canon de Cirque": {"base": 25, "max": 120},
    "L’Enclume Express": {"base": 15, "max": 90},
}


def facteur_affluence(heure):
    """Simule l'évolution de l'affluence sur une journée."""
    courbe = [
        (9, 0.20),
        (10, 0.35),
        (11, 0.60),
        (12, 1.00),
        (13, 0.95),
        (14, 0.90),
        (15, 0.85),
        (16, 0.70),
        (17, 0.50),
        (18, 0.30),
        (19, 0.15),
        (20, 0.05),
        (21, 0.00),
    ]

    if heure < courbe[0][0] or heure >= courbe[-1][0]:
        return 0.0

    for i in range(len(courbe) - 1):
        h1, f1 = courbe[i]
        h2, f2 = courbe[i + 1]

        if h1 <= heure < h2:
            progression = (heure - h1) / (h2 - h1)
            return f1 + (f2 - f1) * progression

    return 0.0


def calculer_attente(nom, heure):
    config = ATTRACTIONS.get(nom)

    if config is None:
        config = {"base": 12, "max": 90}

    affluence = facteur_affluence(heure)

    # Attente de base + effet de l'affluence.
    attente = config["base"] * (0.25 + 1.75 * affluence)

    # Petite variation aléatoire pour éviter des valeurs figées.
    attente *= random.uniform(0.90, 1.10)

    # Arrondi aux 5 minutes, avec un minimum de 5 min.
    attente = round(attente / 5) * 5
    return max(5, min(int(attente), config["max"]))


def mettre_a_jour():
    connexion = mysql.connector.connect(**DB_CONFIG)

    try:
        curseur = connexion.cursor()
        heure = datetime.now().hour

        curseur.execute(
            "SELECT id, nom, statut FROM attractions"
        )
        attractions = curseur.fetchall()

        for identifiant, nom, statut in attractions:
            if statut != "Ouvert":
                attente = None
            else:
                attente = calculer_attente(nom, heure)

            curseur.execute(
                """
                UPDATE attractions
                SET attente = %s
                WHERE id = %s
                """,
                (attente, identifiant),
            )

        connexion.commit()
        print(
            f"[{datetime.now():%H:%M:%S}] "
            f"{len(attractions)} attractions mises à jour."
        )

    finally:
        curseur.close()
        connexion.close()


if __name__ == "__main__":
    print("Simulation des temps d'attente démarrée.")

    while True:
        debut = time.monotonic()

        try:
            mettre_a_jour()
        except Exception as erreur:
            print(f"Erreur : {erreur}")

        # Attend jusqu'à 60 secondes entre deux mises à jour.
        duree = time.monotonic() - debut
        time.sleep(max(1, 60 - duree))
