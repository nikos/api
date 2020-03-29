variable "S3_BACKEND_BUCKET" {
  default = "covid19-civitech-remote" # ! REPLACE WITH YOUR TERRAFORM BACKEND BUCKET
}

variable "S3_BUCKET_REGION" {
  type    = string
  default = "us-east-1"
}

variable "AWS_REGION" {
  type    = string
  default = "us-east-1"
}

variable "TAG_ENV" {
  default = "dev"
}

variable "ENV" {
  default = "PROD"
}

variable "CIDR_PRIVATE" {
  type = list
  default = ["10.0.1.0/24", "10.0.2.0/24"]
}

variable "CIDR_PUBLIC" {
  type = list
  default = ["10.0.101.0/24", "10.0.102.0/24"]
}

variable "stack_name" {
  default = "civictechhub"
}

variable "tags" {
  type = map
  default = {
    environment = "live"
    steck = "civictechhub"
  }
}

variable "password" {
    type = string
    default = ""
}

variable "domain" {
  type = string
  default = "c19ctcl.org"
}
