terraform {
  backend "s3" {
    bucket = "covid19-civitech-remote"
    key    = "covid19-civitech-remote/prod"
    region = "us-east-1"
  }
}

provider "aws" {
  version = "~> 2.0"
  region  = "us-east-1"
}


