
resource "aws_db_subnet_group" "eventsphere" {
  name        = "eventsphere-db-subnet-group"
  description = "Private subnet group for EventSphere MySQL"

  subnet_ids = [
    aws_subnet.private_a.id,
    aws_subnet.private_b.id
  ]

  lifecycle {
    prevent_destroy = true
  }
}
