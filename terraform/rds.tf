resource "aws_db_instance" "eventsphere" {
  identifier        = "eventsphere-mysql"
  engine            = "mysql"
  engine_version    = "8.4.11"
  instance_class    = "db.t4g.micro"
  allocated_storage = 20
  storage_type      = "gp3"
  storage_encrypted = true

  username = "admin"

  db_subnet_group_name   = "eventsphere-db-subnet-group"
  vpc_security_group_ids = ["sg-0d2304690fcf3a995"]

  publicly_accessible = false
  multi_az            = false

  backup_retention_period = 1
  max_allocated_storage   = 1000

  parameter_group_name = "default.mysql8.4"

  auto_minor_version_upgrade = true
  copy_tags_to_snapshot      = true

  deletion_protection = false
  skip_final_snapshot = true

  lifecycle {
    prevent_destroy = true
  }
}
