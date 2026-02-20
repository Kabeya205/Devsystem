############################################
# RÉSEAU DOCKER (ISOLATION)
############################################
resource "docker_network" "test_network" {
  name = "test_network"
}

############################################
# VOLUMES
############################################
resource "docker_volume" "db_data" {
  name = "db_data"
}

resource "docker_volume" "grafana_data" {
  name = "grafana_data"
}

############################################
# CONTENEUR MYSQL
############################################
resource "docker_container" "db" {
  image = "mysql:8.0"
  name  = "db_test"

  networks_advanced {
    name = docker_network.test_network.name
  }

  env = [
    "MYSQL_ROOT_PASSWORD=rootpass",
    "MYSQL_DATABASE=testdb",
    "MYSQL_USER=testuser",
    "MYSQL_PASSWORD=testpass"
  ]

  volumes {
    volume_name    = docker_volume.db_data.name
    container_path = "/var/lib/mysql"
  }
}

############################################
# CONTENEUR APPLICATION FLASK
############################################
resource "docker_container" "web" {
  image = "python:3.11"
  name  = "web_test"

  networks_advanced {
    name = docker_network.test_network.name
  }

  working_dir = "/app"

  volumes {
    host_path      = "C:/devtestenv/app"
    container_path = "/app"
  }

  ports {
    internal = 5000
    external = 5000
  }

  command = [
    "sh",
    "-c",
    "pip install flask && python /app/app.py"
  ]

  depends_on = [docker_container.db]
}

############################################
# GRAFANA (SURVEILLANCE VISUELLE)
############################################
resource "docker_container" "grafana" {
  image = "grafana/grafana:latest"
  name  = "grafana_test"

  networks_advanced {
    name = docker_network.test_network.name
  }

  ports {
    internal = 3000
    external = 3000
  }

  env = [
    "GF_SECURITY_ADMIN_USER=admin",
    "GF_SECURITY_ADMIN_PASSWORD=admin",
    "GF_USERS_ALLOW_SIGN_UP=false"
  ]

  volumes {
    volume_name    = docker_volume.grafana_data.name
    container_path = "/var/lib/grafana"
  }

  depends_on = [
    docker_container.web
  ]
}