output "main_db_credentials" {
  value = {
    password = (length(var.password) > 0) ? var.password : random_string.password_main.result
  }
}

output "myapp-repo" {
  value = aws_ecr_repository.myapp.repository_url
}
output "myapp-arn" {
  value = aws_ecr_repository.myapp.arn
}

output "alb_dns_name" {
  value = aws_alb.myapp.dns_name
}