output "main_db_credentials" {
  value = {
    password = (length(var.password) > 0) ? var.password : random_string.password_main.result
  }
}

output "ecr_image_respository_url" {
  value      = aws_ecr_repository.registry.repository_url
}
output "ecr_image_respository_arn" {
  value      = aws_ecr_repository.registry.arn
}
