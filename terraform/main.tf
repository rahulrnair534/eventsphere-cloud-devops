# Existing EventSphere VPC
data "aws_vpc" "eventsphere" {
  id = "vpc-0abad17500c7481b1"
}

# Existing public subnet
data "aws_subnet" "public_a" {
  id = "subnet-01d9e311530b80f35"
}

# Existing private subnets
data "aws_subnet" "private_a" {
  id = "subnet-0e663b32f19794785"
}

data "aws_subnet" "private_b" {
  id = "subnet-0d849b0774b778352"
}

# Existing security groups
data "aws_security_group" "ec2" {
  id = "sg-0f2f8e5281bcee3d1"
}

data "aws_security_group" "rds" {
  id = "sg-0d2304690fcf3a995"
}

# Infrastructure outputs
output "eventsphere_vpc_id" {
  value = data.aws_vpc.eventsphere.id
}

output "eventsphere_vpc_cidr" {
  value = data.aws_vpc.eventsphere.cidr_block
}

output "public_subnet_id" {
  value = data.aws_subnet.public_a.id
}

output "private_subnet_ids" {
  value = [
    data.aws_subnet.private_a.id,
    data.aws_subnet.private_b.id
  ]
}

output "ec2_security_group_id" {
  value = data.aws_security_group.ec2.id
}

output "rds_security_group_id" {
  value = data.aws_security_group.rds.id
}
