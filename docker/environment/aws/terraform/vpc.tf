
module "base_vpc" {
  source = "github.com/terraform-aws-modules/terraform-aws-vpc"

  name = "base_vpc"
  cidr = "10.0.0.0/16"

  azs             = [format("%sb", var.AWS_REGION), format("%sc", var.AWS_REGION)]
  private_subnets = var.CIDR_PRIVATE
  public_subnets  = var.CIDR_PUBLIC

  enable_nat_gateway = true
  single_nat_gateway = true

  tags = var.tags
}