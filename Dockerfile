# P2P application-specific docker directives
FROM openthc/p2p-base

ADD ./bin bin
ADD ./lib lib
ADD ./webroot webroot
ADD boot.php boot.php
ADD composer.json composer.json
ADD ./etc/ssl etc/ssl

# todo: When ADD/COPY --chown option interpolates env vars, replace that solution with this
RUN chown ${APP_USER}:${APP_USER} ./bin \
    && chown ${APP_USER}:${APP_USER} ./lib \
    && chown ${APP_USER}:${APP_USER} ./webroot \
    && chown ${APP_USER}:${APP_USER} ./boot.php \
    && chown ${APP_USER}:${APP_USER} ./composer.json \
    && chown -R ${APP_USER}:${APP_USER} ./etc

USER ${APP_USER}
RUN composer install || composer upgrade

EXPOSE 443

CMD [ "apache2-foreground" ]