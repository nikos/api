##
## base
##

FROM mysql:8 as base

COPY ./docker/src/files/db/my.cnf /etc/mysql/conf.d/my.cnf

FROM base AS production

FROM base AS development


