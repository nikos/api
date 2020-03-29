[
  {
    "name": "${STACK_NAME}",
    "image": "${REPOSITORY_URL}:latest",
    "networkMode": "awsvpc",
    "essential": true,
    "logConfiguration": {
        "logDriver": "awslogs",
        "options": {
          "awslogs-group": "/ecs/myapp",
          "awslogs-region": "${AWS_REGION}",
          "awslogs-stream-prefix": "ecs"
        }
    },
    "environment": [
      {
        "name": "MYSQL_DATABASE",
        "value": "covidechhub"
      },
      {
        "name": "MYSQL_USER",
        "value": "app"
      },
      {
        "name": "MYSQL_PASS",
        "value": "${DBPASS}"

      }
    ],
    "portMappings": [
      {
        "containerPort": 22,
        "hostPort": 22
      },
      {
        "containerPort": 8080,
        "hostPort": 8080
      }
    ]

  }
]
