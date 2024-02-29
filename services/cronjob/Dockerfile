ARG ALPINE_VERSION
FROM ${ALPINE_VERSION}
ARG TZ
ARG INSTALL_APPS
ARG CONTAINER_PACKAGE_URL


COPY ./install.sh /tmp/install.sh

RUN if [ $CONTAINER_PACKAGE_URL ] ; then sed -i "s/dl-cdn.alpinelinux.org/${CONTAINER_PACKAGE_URL}/g" /etc/apk/repositories ; fi; \
    apk add tzdata \
    && cp "/usr/share/zoneinfo/$TZ" /etc/localtime \
    && echo "$TZ" > /etc/timezone \
    && chmod +x /tmp/install.sh \
    && sh /tmp/install.sh \
    && rm -rf /var/cache/apk/*

WORKDIR /root