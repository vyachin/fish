FROM nginx:alpine AS builder

# nginx:alpine contains NGINX_VERSION environment variable, like so:
# ENV NGINX_VERSION 1.18.0

ENV UPLOAD_VERSION 2.3.0

WORKDIR /usr/src

RUN apk add --no-cache --virtual .build-deps \
  gcc \
  libc-dev \
  make \
  openssl-dev \
  pcre-dev \
  zlib-dev \
  linux-headers \
  curl \
  gnupg \
  libxslt-dev \
  gd-dev \
  geoip-dev

RUN CONFARGS=$(nginx -V 2>&1 | sed -n -e 's/^.*arguments: //p') && \
    echo $CONFARGS & \
    wget "https://nginx.org/download/nginx-${NGINX_VERSION}.tar.gz" -O nginx.tar.gz && \
    wget "https://github.com/vkholodkov/nginx-upload-module/archive/refs/tags/${UPLOAD_VERSION}.tar.gz" -O upload.tar.gz && \
    tar -xzvf nginx.tar.gz && \
    tar -xzvf upload.tar.gz && \
    cd /usr/src/nginx-$NGINX_VERSION && \
    ./configure --with-compat $CONFARGS --add-dynamic-module=../nginx-upload-module-$UPLOAD_VERSION && \
    make && make install

FROM nginx:alpine

COPY --from=builder /usr/local/nginx/modules/ngx_http_upload_module.so /usr/local/nginx/modules/ngx_http_upload_module.so

EXPOSE 80

STOPSIGNAL SIGQUIT

CMD ["nginx", "-g", "daemon off;"]
