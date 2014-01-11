name             "rootsapp"
maintainer       "Tom Seldon"
maintainer_email "tom@tomseldon.co.uk"
description      "Installs WordPress"
version          "0.0.1"

supports 'ubuntu'
supports 'centos'

%w{ nginx php-fpm wordpress-nginx database mysql }.each do |cb|
  depends cb
end

recipe "rootsapp", "Installs WordPress config: Sets up .env file in web root and adds nginx config."
recipe "rootsapp::db", "Creates WordPress database, database user and grants privileges to that user."