#!/bin/sh

echo "============================================"
echo "script   : install.sh"
echo "Install app   : ${INSTALL_APPS}"
echo "Work directory            : ${PWD}"
echo "============================================"

export APPS=",${INSTALL_APPS},"


if [[ -z "${APPS##*,bash,*}" ]]; then
    echo "---------- Install bash ----------"
    apk add bash
fi


if [[ -z "${APPS##*,git,*}" ]]; then
    echo "---------- Install git ----------"
    apk add git 
fi



if [[ -z "${APPS##*,go,*}" ]]; then
    echo "---------- Install go ----------"
    apk add go
fi



if [[ -z "${APPS##*,nodejs,*}" ]]; then
    echo "---------- Install nodejs ----------"
    apk add nodejs
fi



if [[ -z "${APPS##*,python3,*}" ]]; then
    echo "---------- Install python3 ----------"
    apk add python3
fi


if [[ -z "${APPS##*,openssh,*}" ]]; then
    echo "---------- Install openssh ----------"
    apk add openssh
fi


if [[ -z "${APPS##*,rust,*}" ]]; then
    echo "---------- Install rust ----------"
    apk add rust
fi