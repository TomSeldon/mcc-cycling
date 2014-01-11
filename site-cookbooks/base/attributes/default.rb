# Remove test user and database from MySQL
#
default['mysql']['remove_anonymous_users'] = true
default['mysql']['remove_test_database']   = true