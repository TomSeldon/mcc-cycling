default[:wp_cookbook][:user]      = 'www-data'
default[:wp_cookbook][:hostname]  = 'mcc-cycling.dev'
default[:wp_cookbook][:dir]       = '/var/www'
default[:wp_cookbook][:wp_cli]    = 'vendor/bin/wp'
default[:wp_cookbook][:theme_dir] = 'app/themes/mcc-cycling'
default[:wp_cookbook][:db_name]   = 'mcc-cycling'

default[:wp_cookbook][:wp_title]       = 'MCC Cycling'
default[:wp_cookbook][:wp_admin_user]  = 'tom.seldon'
default[:wp_cookbook][:wp_admin_pass]  = 'admin'
default[:wp_cookbook][:wp_admin_email] = 'tom@tomseldon.co.uk'

default[:wp_cookbook][:wp_import]       = false
default[:wp_cookbook][:wp_import_dump]  = 'prod.sql'
