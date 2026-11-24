resource "aws_route_table" "public" {
  vpc_id = aws_vpc.eventsphere.id

  route {
    cidr_block = "0.0.0.0/0"
    gateway_id = aws_internet_gateway.eventsphere.id
  }

  tags = {
    Name = "routetable-public"
  }

  lifecycle {
    prevent_destroy = true
  }
}
resource "aws_route_table" "private" {
  vpc_id = aws_vpc.eventsphere.id

  tags = {
    Name = "routetable-private"
  }

  lifecycle {
    prevent_destroy = true
  }
}
