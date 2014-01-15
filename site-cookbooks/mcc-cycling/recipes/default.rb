#
# Cookbook Name:: mcc-cycling
# Recipe:: default
#

packages          = ['php-pear', 'php5-dev', 'apache2-dev', 'gcc', 'make', 'zlib1g-dev', 'libpcre3-dev']
packagesInstalled = 0;

# Force apt-get update
execute "compile-time-apt-get-update" do
  command "apt-get update"
  ignore_failure true
  action :nothing
end.run_action(:run)

# Install required packages
for p in packages do
    execute "Install: #{p}" do
        command         "apt-get -q -y install #{p}"
        ignore_failure  true
        action          :nothing
    end.run_action(:run)

    packagesInstalled += 1
end

# Include additional recipes
include_recipe 'apt::default'
include_recipe 'build-essential'
include_recipe 'git'
include_recipe 'mysql::server'
include_recipe 'mysql::client'
include_recipe 'php'
include_recipe 'composer'

php_pear "apc" do
  action            :install
  preferred_state   'stable'
end

include_recipe 'wp-cookbook'