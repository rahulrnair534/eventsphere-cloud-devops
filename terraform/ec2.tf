resource "aws_instance" "eventsphere" {
  ami                         = "ami-0037855f86d0a04b4"
  instance_type               = "t3.micro"
  key_name                    = "aws-demo-july"
  subnet_id                   = "subnet-01d9e311530b80f35"
  private_ip                  = "10.0.0.106"
  vpc_security_group_ids      = ["sg-0f2f8e5281bcee3d1"]
  associate_public_ip_address = true

  ebs_optimized = true
  monitoring    = false

  root_block_device {
    volume_size           = 8
    volume_type           = "gp3"
    iops                  = 3000
    encrypted             = false
    delete_on_termination = true
  }

  metadata_options {
    http_endpoint               = "enabled"
    http_tokens                 = "required"
    http_put_response_hop_limit = 2
  }

  source_dest_check = true

  tags = {
    Name = "eventsphere-app-server"
  }

  lifecycle {
    prevent_destroy = true
  }
}
