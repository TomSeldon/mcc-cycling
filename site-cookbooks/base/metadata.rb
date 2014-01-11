name             "base"
maintainer       "Tom Seldon"
maintainer_email "tom@tomseldon.co.uk"
description      "Configures server with base packages required."
version          "0.0.1"

supports 'ubuntu'
supports 'centos'

%w{ rootsapp ohai apt yum build-essential screen composer vim nano git database mysql nginx php php-fpm phpunit }.each do |cb|
  depends cb
end

recipe "base", "Installs base packages required"
recipe "base::web-server", "Installs base packages required for web server (Nginx, PHP and MySQL)"
