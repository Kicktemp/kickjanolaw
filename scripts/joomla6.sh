#!/usr/bin/env bash

SCRIPTPATH="$( cd "$(dirname "$0")" ; pwd -P )"
INSTALLDIR=$SCRIPTPATH/../dist6/

if [ ! -d INSTALLDIR ]; then
 mkdir -p ${INSTALLDIR}
fi

current="$(curl -fsSL 'https://downloads.joomla.org/api/v1/latest/cms' | jq -r '.branches[] | select(.branch=="Joomla! 6").version')"

curl -o ${INSTALLDIR}joomla.tar.gz -SL https://github.com/joomla/joomla-cms/releases/download/${current}/Joomla_${current}-Stable-Full_Package.tar.gz
tar xfvj ${INSTALLDIR}joomla.tar.gz -C ${INSTALLDIR}
rm -f ${INSTALLDIR}joomla.tar.gz
echo "Joomla! 6 package downloaded and extracted to ${INSTALLDIR}"
echo "Current Joomla! 6 version: ${current}"
