# Étape 1 : builder
FROM node:18-alpine AS builde
WORKDIR /app

RUN apk update && apk upgrade

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Étape 2 : servir le build
FROM nginx:alpine
COPY --from=builder /app/dist /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
