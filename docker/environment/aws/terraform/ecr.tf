resource "aws_ecr_repository" "myapp" {
  name = var.stack_name
}

