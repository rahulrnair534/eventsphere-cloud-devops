resource "aws_subnet" "public_a" {
  vpc_id                  = aws_vpc.eventsphere.id
  cidr_block              = "10.0.0.0/24"
  availability_zone       = "ap-south-1a"
  map_public_ip_on_launch = true

  tags = {
    Name = "public-subnet-a"
  }

  lifecycle {
    prevent_destroy = true
  }
}

resource "aws_subnet" "public_b" {
  vpc_id                  = aws_vpc.eventsphere.id
  cidr_block              = "10.0.1.0/24"
  availability_zone       = "ap-south-1b"
  map_public_ip_on_launch = true

  tags = {
    Name = "public-subnet-b"
  }

  lifecycle {
    prevent_destroy = true
  }
}

resource "aws_subnet" "private_a" {
  vpc_id                  = aws_vpc.eventsphere.id
  cidr_block              = "10.0.10.0/24"
  availability_zone       = "ap-south-1a"
  map_public_ip_on_launch = false

  tags = {
    Name = "private-subnet-a"
  }

  lifecycle {
    prevent_destroy = true
  }
}

resource "aws_subnet" "private_b" {
  vpc_id                  = aws_vpc.eventsphere.id
  cidr_block              = "10.0.11.0/24"
  availability_zone       = "ap-south-1b"
  map_public_ip_on_launch = false

  tags = {
    Name = "private-subnet-b"
  }

  lifecycle {
    prevent_destroy = true
  }
}
