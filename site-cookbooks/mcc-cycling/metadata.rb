name             'mcc-cycling'
maintainer       'Tom Seldon'
maintainer_email 'tom@tomseldon.co.uk'
description      'Configures web-server for MCC Cycling website'
version          '0.0.1'

recipe 'mcc-cycling::default', 'Base server setup'

supports 'ubuntu'

depends 'build-essential'
depends 'apt'
depends 'git'
depends 'mysql'
depends 'php'
depends 'wp-cookbook'