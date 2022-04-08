#!/bin/sh

SYSTEM_ROOT='/var/www/glueynotes.com'
JOB=$1
PHPCMD='/usr/local/bin/php'

cd ${SYSTEM_ROOT}
#. ${SYSTEM_ROOT}/Jobs/${JOB}/run_job.sh

${PHPCMD} ${SYSTEM_ROOT}/Jobs/${JOB}.php