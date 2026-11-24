resource "aws_internet_gateway" "eventsphere" {
  vpc_id = aws_vpc.eventsphere.id

  tags = {
    Name = "demo-igw-rahul"
  }

  lifecycle {
    prevent_destroy = true
  }
}
