resource "random_string" "password_main" {
  length           = 16
  special          = false
  min_lower        = 1
  min_upper        = 1
  min_special      = 1
  override_special = ",.+-%!"
}

resource "aws_rds_cluster" "db" {
  cluster_identifier      = format("%s-mysql", var.stack_name)
  engine                  = "aurora-mysql"
  engine_version          = "5.7.mysql_aurora.2.03.2"
  availability_zones      = [format("%sa", var.AWS_REGION), format("%sb", var.AWS_REGION), format("%sc", var.AWS_REGION)]
  database_name           = var.stack_name
  master_username         = "app"
  master_password         = (length(var.password) > 0) ? var.password : random_string.password_main.result
  backup_retention_period = 5
  preferred_backup_window = "07:00-09:00"
  final_snapshot_identifier = "backup"
  skip_final_snapshot     = true
}