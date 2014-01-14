name             'mcc-cycling'
maintainer       'Tom Seldon'
maintainer_email 'tom@tomseldon.co.uk'
description      'Configures web-server for MCC Cycling website'
version          '0.0.1'

recipe 'mcc-cycling::default', 'Base server setup'

depends 'build-essential'
depends 'apt', '1.7.0'
depends 'package'