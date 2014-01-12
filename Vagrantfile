# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    # Change this host name to something more relevant
    config.vm.hostname          = "mcc-cycling.dev"

    config.omnibus.chef_version = :latest
    config.vm.box               = "precise32"
    config.vm.network "private_network", ip: "192.168.50.4"

    config.vm.provider :virtualbox do |v|
        v.customize ["modifyvm", :id, "--memory", 1024]
    end

    config.vm.synced_folder "./", "/srv/web",
        owner: "vagrant",
        group: "www-data",
        mount_options: ["dmode=775,fmode=664"]

    config.vm.provision :chef_solo do |chef|
        #
        # Presently, only the `cookbooks_path` setting is required.
        # No custom data bags, roles or environments are used (at least at time
        # of writing; environments are not supported with chef-solo anyway).
        #
        # The settings have been left here so they may be used if and
        # when appropriate.
        #
        chef.cookbooks_path                     = ["cookbooks","site-cookbooks"]
        chef.data_bags_path                     = ["chef/data_bags"]
        chef.environments_path                  = ["chef/environments"]
        chef.roles_path                         = ["chef/roles"]
        #chef.encrypted_data_bag_secret_key_path = ''

        chef.log_level = :debug

        chef.add_recipe "mysql::server"
        chef.add_recipe "database::mysql"
        chef.add_recipe "wp-cookbook"
        chef.add_recipe "wp-cookbook::setup"

        chef.json = {
            "mysql" => {
                "server_debian_password"    => "password",
                "server_root_password"      => "password",
                "server_repl_password"      => "password"
            }
        }
    end
end