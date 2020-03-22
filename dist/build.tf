variable "image_name" {
  type = string
  default = "covid-19-civitech"
}

module "codecommit-cicd" {
  source                    = "git::https://github.com/slalompdx/terraform-aws-codecommit-cicd.git?ref=master"
  repo_name                 = "civitech"                                                             # Required
  organization_name         = "covid19-civitech"                                                                  # Required
  repo_default_branch       = "master"                                                                         # Default value
  aws_region                = "us-east-1"                                                                      # Default value
  char_delimiter            = "-"                                                                              # Default value
  environment               = "prod"                                                                            # Default value
  build_timeout             = "5"                                                                              # Default value
  build_compute_type        = "BUILD_GENERAL1_SMALL"                                                           # Default value
  build_image               = "aws/codebuild/docker:17.09.0"                                                   # Default value
  build_privileged_override = "true"                                                                           # Default value
  test_buildspec            = "buildspec_test.yml"                                                             # Default value
  package_buildspec         = "buildspec.yml"                                                                  # Default value
  force_artifact_destroy    = "true"                                                                           # Default value
}


resource "aws_iam_role_policy" "codebuild_policy" {
  name = "serverless-codebuild-automation-policy"
  role = module.codecommit-cicd.codebuild_role

  policy = <<POLICY
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Action": [
        "ecr:BatchCheckLayerAvailability",
        "ecr:CompleteLayerUpload",
        "ecr:GetAuthorizationToken",
        "ecr:InitiateLayerUpload",
        "ecr:PutImage",
        "ecr:UploadLayerPart"
      ],
      "Resource": "aws_ecr_repository.registry.arn",
      "Effect": "Allow"
    }
  ]
}
POLICY
}

output "repo_url" {
  value      = module.codecommit-cicd.clone_repo_ssh
}

output "codepipeline_role" {
  value      = module.codecommit-cicd.codepipeline_role
}

output "codebuild_role" {
  value      = module.codecommit-cicd.codebuild_role
}

