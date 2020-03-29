resource "aws_acm_certificate" "cert" {
  domain_name       = var.domain
  subject_alternative_names = [var.domain, format("www.%s", var.domain)]
  validation_method = "DNS"

  tags = {
    Environment = "live"
  }

  lifecycle {
    create_before_destroy = true
  }
}

data "aws_route53_zone" "zone" {
  name         = format("%s.", var.domain)
  private_zone = false
}

resource "aws_route53_record" "cert_validation" {
  name    = aws_acm_certificate.cert.domain_validation_options.0.resource_record_name
  type    = aws_acm_certificate.cert.domain_validation_options.0.resource_record_type
  zone_id = data.aws_route53_zone.zone.id
  records = [aws_acm_certificate.cert.domain_validation_options.0.resource_record_value]
  ttl     = 60
}
resource "aws_route53_record" "cert_validation1" {
  name    = aws_acm_certificate.cert.domain_validation_options.1.resource_record_name
  type    = aws_acm_certificate.cert.domain_validation_options.1.resource_record_type
  zone_id = data.aws_route53_zone.zone.id
  records = [aws_acm_certificate.cert.domain_validation_options.1.resource_record_value]
  ttl     = 60
}
resource "aws_acm_certificate_validation" "cert" {
  certificate_arn         = aws_acm_certificate.cert.arn
  validation_record_fqdns = [aws_route53_record.cert_validation.fqdn, aws_route53_record.cert_validation1.fqdn]
}


resource "aws_route53_record" "domain_to_alb" {
  name = var.domain
  type = "A"
  zone_id = data.aws_route53_zone.zone.id
  alias {
    name = aws_alb.myapp.dns_name
    zone_id = aws_alb.myapp.zone_id
    evaluate_target_health = false
  }
}