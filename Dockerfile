FROM php:7.1-cli

ARG LIBRDKAFKA_VER="0.9.5"

# install librdkafka dependencies
RUN apt-get install zlib1g && \
    apt-get update && \
    apt-get install -y python

# install librdkafka
RUN BUILD_DIR="$(mktemp -d)" && \
    \
        curl -o "$BUILD_DIR/librdkafka.tar.gz" -SL "https://github.com/edenhill/librdkafka/archive/v$LIBRDKAFKA_VER.tar.gz" && \
        mkdir -p "$BUILD_DIR/librdkafka-$LIBRDKAFKA_VER" && \
        tar \
          --extract \
          --file "$BUILD_DIR/librdkafka.tar.gz" \
          --directory "$BUILD_DIR/librdkafka-$LIBRDKAFKA_VER" \
          --strip-components 1 && \
    \
        cd "$BUILD_DIR/librdkafka-$LIBRDKAFKA_VER" && \
        ./configure \
          --prefix=/usr && \
        make -j "$(getconf _NPROCESSORS_ONLN)" && \
        make install && \
    \
        rm -rf $BUILD_DIR


# install kafka extension
RUN pecl install rdkafka

COPY ./php.ini /usr/local/etc/php/conf.d/

VOLUME ["/var/www/php-kafka"]

WORKDIR /var/www/php-kafka

