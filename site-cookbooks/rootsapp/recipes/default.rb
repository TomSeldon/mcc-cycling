# Cookbook name:: rootsapp
# Recipe:: default
#

include_recipe "rootsapp::db"

directory node[:rootsapp][:root] do
    action  :create
    user    node['nginx']['user']
    group   node['nginx']['group']
end

template "#{node[:rootsapp][:root]}/.env" do
    source ".env.erb"
    action :create_if_missing
    user    node['nginx']['user']
    group   node['nginx']['group']
end

# Create the WordPress nginx config
wordpress_nginx_site "#{node[:rootsapp][:host]}" do
    host        node[:rootsapp][:host]
    root        node[:rootsapp][:root]
    notifies    :reload, 'service[nginx]'
end