set :stage, 'staging'

# Simple Role Syntax
# ==================
#role :app, %w{deploy@example.com}
#role :web, %w{deploy@example.com}
#role :db,  %w{deploy@example.com}

# Extended Server Syntax
# ======================
server '146.185.178.161', user: 'deploy', roles: %w{web app db}

# you can set custom ssh options
# it's possible to pass any option but you need to keep in mind that net/ssh understand limited list of options
# you can see them in [net/ssh documentation](http://net-ssh.github.io/net-ssh/classes/Net/SSH.html#method-c-start)
# set it globally
  set :ssh_options, {
    keys: %w(/Users/Tom/.ssh/github_rsa),
    forward_agent: true,
    #verbose: :debug
    #auth_methods: %w(password)
  }

fetch(:default_env).merge!(wp_env: :staging)

