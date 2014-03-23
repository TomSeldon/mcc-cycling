server {
    listen       80;
    server_name  <%= @host %>;
    root         <%= @root %>;
    access_log   <%= node['nginx']['log_dir'] %>/<%= @name %>.access.log;
    error_log    <%= node['nginx']['log_dir'] %>/<%= @name %>.error.log;

    # Stop some private files being shown
    location ~ /(config|Capfile|Cheffile|Gemfile(\.lock)?|composer(\.lock|\.json)|\.env|Vagrantfile|.gitignore|(.+)\.md|(.+)\.yml) {
      deny all;
    }

    # Increase post size
    client_max_body_size 2m;

    # BEGIN W3TC Browser Cache
    gzip on;
    gzip_types text/css text/x-component application/x-javascript application/javascript text/javascript text/x-js text/richtext image/svg+xml text/plain text/xsd text/xsl text/xml image/x-icon;
    location ~ \.(css|htc|js|js2|js3|js4)$ {
        expires 31536000s;
        add_header Pragma "public";
        add_header Cache-Control "max-age=31536000, public";
        add_header X-Powered-By "W3 Total Cache/0.9.3";
    }
    location ~ \.(html|htm|rtf|rtx|svg|svgz|txt|xsd|xsl|xml)$ {
        expires 3600s;
        add_header Pragma "public";
        add_header Cache-Control "max-age=3600, public";
        add_header X-Powered-By "W3 Total Cache/0.9.3";
    }
    location ~ \.(asf|asx|wax|wmv|wmx|avi|bmp|class|divx|doc|docx|eot|exe|gif|gz|gzip|ico|jpg|jpeg|jpe|json|mdb|mid|midi|mov|qt|mp3|m4a|mp4|m4v|mpeg|mpg|mpe|mpp|otf|odb|odc|odf|odg|odp|ods|odt|ogg|pdf|png|pot|pps|ppt|pptx|ra|ram|svg|svgz|swf|tar|tif|tiff|ttf|ttc|wav|wma|wri|xla|xls|xlsx|xlt|xlw|zip)$ {
        expires 31536000s;
        add_header Pragma "public";
        add_header Cache-Control "max-age=31536000, public";
        add_header X-Powered-By "W3 Total Cache/0.9.3";
    }
    # END W3TC Browser Cache

    # BEGIN W3TC Minify core
    rewrite ^/app/cache/minify.*/w3tc_rewrite_test$ /app/plugins/w3-total-cache/pub/minify.php?w3tc_rewrite_test=1 last;
    rewrite ^/app/cache/minify/(.+/[X]+\.css)$ /app/plugins/w3-total-cache/pub/minify.php?test_file=$1 last;
    rewrite ^/app/cache/minify/(.+\.(css|js))$ /app/plugins/w3-total-cache/pub/minify.php?file=$1 last;
    # END W3TC Minify core

    # Site map rewrites
    rewrite ^/sitemap_index\.xml$ /index.php?sitemap=1 last;
    rewrite ^/([^/]+?)-sitemap([0-9]+)?\.xml$ /index.php?sitemap=$1&sitemap_n=$2 last;

    <% if @code -%>
    <%= @code %>
    <% end -%>

    include <%= node['nginx']['dir'] %>/wordpress.conf;
}
