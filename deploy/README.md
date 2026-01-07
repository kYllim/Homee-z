Déploiement — build-on-VPS (OVH Ubuntu 24.04)

But : builder le front et le back directement sur le VPS via Docker Compose.

Prérequis
- VPS Ubuntu 24.04, accès SSH
- Nom de domaine pointant sur l'IP du VPS
- Clé SSH configurée pour accès git (si repo privé) ou possibilité de transférer les fichiers

Étapes (résumé rapide)
1) Installer Docker + docker-compose plugin
2) Cloner le repo sur le VPS
3) Créer un fichier `.env` (copier `deploy/.env.example`) et remplir les valeurs
4) Copier vos clefs JWT (private.pem/public.pem) dans `deploy/keys` et sécuriser permissions
5) Lancer : sudo docker compose -f compose.prod.yaml up -d --build
6) Obtenir certifs via certbot (staging d'abord), puis repasser en prod
7) Lancer migrations et vérifier

Commandes détaillées (à exécuter sur le VPS)

# 1) Installer Docker
sudo apt update && sudo apt install -y ca-certificates curl gnupg lsb-release
curl -fsSL https://get.docker.com | sh
sudo apt-get install -y docker-compose-plugin

# 2) Préparer l'arborescence
mkdir -p ~/homee-z/deploy/keys ~/homee-z/certbot/www ~/homee-z/certbot/conf
cd ~/homee-z

# 3) Récupérer le code
# Si repo public :
# git clone <repo_url> .
# Si privé : configurez une clé SSH ou transférez les fichiers via scp/rsync

# 4) Configuration
cp deploy/.env.example deploy/.env
# Modifier deploy/.env : DOMAIN, LETSENCRYPT_EMAIL, APP_SECRET, POSTGRES_PASSWORD, VITE_API_URL, etc.

# 5) Copier les clefs JWT (depuis votre poste local)
# Exemple PowerShell local -> VPS :
# scp .\back\config\jwt\private.pem user@VPS_IP:~/homee-z/deploy/keys/
# scp .\back\config\jwt\public.pem user@VPS_IP:~/homee-z/deploy/keys/

# Protéger les permissions
chmod 640 deploy/keys/private.pem
chmod 644 deploy/keys/public.pem

# 6) Build & start (build sur le VPS)
sudo docker compose -f compose.prod.yaml up -d --build

# 7) Certbot (staging test)
# Assurez-vous que nginx (service `nginx`) expose /.well-known/acme-challenge via /homee-z/certbot/www
sudo docker run --rm -v "$(pwd)/certbot/conf:/etc/letsencrypt" -v "$(pwd)/certbot/www:/var/www/certbot" certbot/certbot certonly --webroot --webroot-path=/var/www/certbot --staging -d yourdomain.tld --email you@example.com --agree-tos --no-eff-email

# 8) Si staging OK -> obtenir les vrais certificats (sans --staging)
sudo docker run --rm -v "$(pwd)/certbot/conf:/etc/letsencrypt" -v "$(pwd)/certbot/www:/var/www/certbot" certbot/certbot certonly --webroot --webroot-path=/var/www/certbot -d yourdomain.tld --email you@example.com --agree-tos --no-eff-email

# 9) Redémarrer nginx pour prendre en compte les certs
sudo docker compose -f compose.prod.yaml restart nginx

# 10) Migrations Doctrine
sudo docker compose -f compose.prod.yaml exec backend php bin/console doctrine:migrations:migrate --no-interaction

Vérifications après déploiement
- sudo docker compose -f compose.prod.yaml ps
- sudo docker compose -f compose.prod.yaml logs -f --tail=200 nginx
- sudo docker compose -f compose.prod.yaml logs -f backend
- Tester l'API public et l'application frontend

Sécurité et bonnes pratiques
- Ne commitez pas `deploy/.env` ni `deploy/keys`.
- Activez un firewall (ufw): autoriser 22, 80, 443
- Prévoir backup régulier de la base (pg_dump)
- Planifier `certbot renew` (cron ou container) et reload nginx après renouvellement

Je peux maintenant :
- Pré-remplir `deploy/.env.example` avec votre domaine + email si vous me les fournissez
- Générer un script bash plus complet pour automatiser ces étapes
- Vous accompagner pas-à-pas sur le VPS (exécutez les commandes et me copiez les logs si erreur)
