FROM php:8.3.10-cli-alpine3.20

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN adduser -DHu 1001 app
USER app

WORKDIR /app

CMD ["ash"]
