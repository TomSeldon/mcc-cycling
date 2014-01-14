#
# Default base attributes.
#

# build-essential settings
override['build-essential']['compiletime']  = true;

# MySQL Settings
override['mysql']['remove_anonymous_users'] = true;
override['mysql']['remove_test_database']   = true;