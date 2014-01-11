# Cookbook name:: base
# Recipe:: web-server
#

include_recipe "database"
include_recipe "mysql::server"
include_recipe "nginx"
include_recipe "php"
include_recipe "php::module_apc"
include_recipe "php::module_curl"
include_recipe "php::module_gd"
include_recipe "php::module_mysql"
include_recipe "php-fpm"
include_recipe "phpunit"

nginx_site 'default' do
  enable false
end
