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

data "aws_iam_policy_document" "codepipeline_ecr_policy" {
  statement {
    sid = "1"

    actions = [
        "ecr:BatchCheckLayerAvailability",
        "ecr:CompleteLayerUpload",
        "ecr:GetAuthorizationToken",
        "ecr:InitiateLayerUpload",
        "ecr:PutImage",
        "ecr:UploadLayerPart"
    ]

    resources = [
      aws_ecr_repository.registry.arn,
    ]
  }
}


resource "aws_iam_policy" "codebuild_policy" {
  name = "serverless-codebuild-automation-policy"
  path = "/"
  policy = data.aws_iam_policy_document.codepipeline_ecr_policy.json
}

resource "aws_iam_policy_attachment" "codebuild_allow_ecr" {
  name       = "codebuild_ecr_attachment"
  roles      = [module.codecommit-cicd.codepipeline_role, module.codecommit-cicd.codebuild_role]
  policy_arn = aws_iam_policy.codebuild_policy.arn
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

