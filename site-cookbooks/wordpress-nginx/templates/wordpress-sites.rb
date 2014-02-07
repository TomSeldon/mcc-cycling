server {
  listen       80;
  server_name  <%= @host %>;
  root         <%= @root %>;
  access_log   <%= node['nginx']['log_dir'] %>/<%= @name %>.access.log;
  error_log    <%= node['nginx']['log_dir'] %>/<%= @name %>.error.log;

  # BEGIN W3TC Browser Cache
  gzip on;
  gzip_types text/css text/x-component application/x-javascript application/javascript text/javascript text/x-js text/richtext image/svg+xml text/plain text/xsd text/xsl text/xml image/x-icon;
  # END W3TC Browser Cache

  # Site map rewrites
  rewrite ^/sitemap_index\.xml$ /index.php?sitemap=1 last;
  rewrite ^/([^/]+?)-sitemap([0-9]+)?\.xml$ /index.php?sitemap=$1&sitemap_n=$2 last;

  <% if @code -%>
  <%= @code %>
  <% end -%>

  include <%= node['nginx']['dir'] %>/wordpress.conf;
}
