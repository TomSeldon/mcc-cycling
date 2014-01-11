# Cookbook name:: rootsapp
# Recipe:: db
#
# Sets up the MySQL database for the WordPress site.
#

include_recipe "mysql::server"
include_recipe "mysql::client"
include_recipe "database::mysql"

# Database connection credentials
mysql_connection_params = {
    :host       => 'localhost',
    :username   => 'root',
    :password   => node[:mysql][:server_root_password]
}

# Create the database
mysql_database node[:rootsapp][:database_name] do
    notifies    :run, 'execute[apt-get update]', :immediately
    connection  mysql_connection_params
    action      :create
end

# Give the new user full access to this database
mysql_database_user node[:rootsapp][:database_user] do
  connection     mysql_connection_params
  password       node[:rootsapp][:database_password]
  database_name  node[:rootsapp][:database_name]
  host          'localhost'
  privileges    [:all]
  action        [:create, :grant]
end