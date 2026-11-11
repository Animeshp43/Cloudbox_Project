
# Use the official lightweight Nginx image as our base
FROM nginx:alpine

# Copy our custom Nginx configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Copy our dashboard into the Nginx web directory
COPY index.html /usr/share/nginx/html/index.html

# Document that the container listens on port 80
EXPOSE 80

