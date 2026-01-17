# Réseau isolé
resource "docker_network" "env_net" {
  name = "${var.env_name}_net"
}

# Volume base de données
resource "docker_volume" "db_data" {
  name = "${var.env_name}_db_data"
}

# Image PHP
resource "docker_image" "php" {
  name = "php:8.2-fpm"
}

# Image Nginx
resource "docker_image" "nginx" {
  name = "nginx:alpine"
}

# Image MySQL
resource "docker_image" "mysql" {
  name = "mysql:8"
}

# Conteneur PHP
resource "docker_container" "php" {
  name  = "${var.env_name}_app"
  image = docker_image.php.image_id

  networks_advanced {
    name = docker_network.env_net.name
  }
}

# Conteneur MySQL
resource "docker_container" "db" {
  name  = "${var.env_name}_db"
  image = docker_image.mysql.image_id

  env = [
    "MYSQL_ROOT_PASSWORD=${var.mysql_root_password}",
    "MYSQL_DATABASE=testdb"
  ]

  volumes {
    volume_name    = docker_volume.db_data.name
    container_path = "/var/lib/mysql"
  }

  networks_advanced {
    name = docker_network.env_net.name
  }
}

# Conteneur Nginx
resource "docker_container" "web" {
  name  = "${var.env_name}_web"
  image = docker_image.nginx.image_id

  ports {
    internal = 80
    external = var.web_port
  }

  networks_advanced {
    name = docker_network.env_net.name
  }
}
