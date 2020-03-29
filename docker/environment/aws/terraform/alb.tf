resource "aws_alb" "myapp" {
  name = var.stack_name
  internal = false

  security_groups = [
    aws_security_group.ecs.id,
    aws_security_group.alb.id,
  ]

  subnets = [
    module.base_vpc.public_subnets[0],
    module.base_vpc.public_subnets[1]
  ]
}

resource "aws_alb_target_group" "myapp" {
  name = var.stack_name
  protocol = "HTTP"
  port = "3000"
  vpc_id = module.base_vpc.vpc_id
  target_type = "ip"

  health_check {
    path = "/"
  }
}

resource "aws_alb_listener" "myapp" {
  load_balancer_arn = aws_alb.myapp.arn
  port = "443"
  protocol = "HTTPS"

  default_action {
    target_group_arn = aws_alb_target_group.myapp.arn
    type = "forward"
  }
  certificate_arn = aws_acm_certificate_validation.cert.certificate_arn

  depends_on = [aws_alb_target_group.myapp]
}



