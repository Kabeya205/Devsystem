variable "env_name" {
  description = "Nom de l'environnement de test"
  type        = string
}

variable "web_port" {
  description = "Port d'accès à l'application PHP"
  type        = number
}

variable "mysql_root_password" {
  type      = string
  sensitive = true
  default   = "root"
}
