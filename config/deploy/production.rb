set :stage, :production

# Simple Role Syntax
# ==================
#role :app, %w{deploy@162.243.228.92}
#role :web, %w{deploy@162.243.228.92}
#role :db,  %w{deploy@162.243.228.92}

# Extended Server Syntax
# ======================
server '162.243.228.92', user: 'deploy', roles: %w{web app db}

# you can set custom ssh options
# it's possible to pass any option but you need to keep in mind that net/ssh understand limited list of options
# you can see them in [net/ssh documentation](http://net-ssh.github.io/net-ssh/classes/Net/SSH.html#method-c-start)
# set it globally
  set :ssh_options, {
    #keys: %w(/Users/Tom/.ssh/github_rsa),
    #forward_agent: true,
    #verbose: :debug
    #auth_methods: %w(password)
  }

fetch(:default_env).merge!(wp_env: :production)

