#
# build the target container
#
FROM public.ecr.aws/docker/library/php:8.2-apache

# update the packages
#RUN apk update && apk upgrade && apk add bash tzdata ca-certificates curl && rm -fr /var/cache/apk/*

# update the packages
RUN apt-get -y update && \
        apt-get -y upgrade 

# set the timezone appropriatly
ENV TZ=America/New_York
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Specify home
ENV APP_HOME=/drupal-netbadge
WORKDIR $APP_HOME

# port 
EXPOSE 80

# Add the build tag
ARG BUILD_TAG
RUN test -n "$BUILD_TAG" && touch $APP_HOME/buildtag.build-$BUILD_TAG || touch $APP_HOME/buildtag.build-0

#
# end of file
#
