cookbook_path               ["cookbooks","site-cookbooks"]
node_path                   "chef/nodes"
role_path                   "chef/roles"
environment_path            "chef/environments"
data_bag_path               "chef/data_bags"
#encrypted_data_bag_secret  "data_bag_key"

knife[:berkshelf_path] = "cookbooks"