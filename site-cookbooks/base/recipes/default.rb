# Cookbook name:: base
# Recipe:: default
#

include_recipe "ohai"
include_recipe "build-essential"
include_recipe "apt"
include_recipe "yum"
include_recipe "composer"
include_recipe "vim"
include_recipe "nano"
include_recipe "git"
include_recipe "screen"

cookbook_file "profile" do
    path    "#{ENV['PWD']}/.bash_profile"
    action  :create
    owner   ENV['SUDO_USER']
    group   ENV['SUDO_USER']
end

cookbook_file "vcprompt" do
    path    "/usr/bin/vcprompt"
    action  :create_if_missing
    owner   ENV['SUDO_USER']
    group   ENV['SUDO_USER']
    mode    "0755"
end