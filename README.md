# tp_poo airbnb

Site de réservations de locations en ligne

# -- en cas de problème de permission pour ajouter une image à une location (linux) --
Dans l'explorateur de fichier, donner au dossier public et à tous ses dossiers enfants les permissions
(Création et suppression des) pour tous les utilisateurs (Propriétaire, Groupe et Autres)

# -- à mettre dans le .env --

# Connexion à la base de données
db_host="database"
db_name="airbnb"
db_user="admin"
db_pass="admin"

# Sécurité
security_salt = "c56a7523d96942a834b9cdc249bd4e8c7aa9" # Chaîne "sel" pour le hachage
security_pepper = "8d746680fd4d7cbac57fa9f033115fc52196" # Chaîne "poivre" pour le hachage


# -- accès aux comptes déjà créés

# mdp comptes : PrenomNom1234
# Compte annoceur
- james@doe.com
- jake@doe.com

# Compte utilisateur
- jane@doe.com
- james@doe.com

