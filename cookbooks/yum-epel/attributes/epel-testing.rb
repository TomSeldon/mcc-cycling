default['yum']['epel-testing']['repositoryid'] = 'epel-testing'

case node['platform']
when 'amazon'
  default['yum']['epel-testing']['description'] = 'Extra Packages for Enterprise Linux 6 - $basearch'
  default['yum']['epel-testing']['mirrorlist'] = 'http://mirrors.fedoraproject.org/mirrorlist?repo=epel-6&arch=$basearch'
  default['yum']['epel-testing']['gpgkey'] = 'http://dl.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL-6'
else
  case node['platform_version'].to_i
  when 5
    default['yum']['epel-testing']['description'] = 'Extra Packages for Enterprise Linux 5 - Testing - $basearch'
    default['yum']['epel-testing']['mirrorlist'] = 'http://mirrors.fedoraproject.org/mirrorlist?repo=testing-epel5&arch=$basearch'
    default['yum']['epel-testing']['gpgkey'] = 'http://dl.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL'
  when 6
    default['yum']['epel-testing']['description'] = 'Extra Packages for Enterprise Linux 6 - Testing - $basearch'
    default['yum']['epel-testing']['mirrorlist'] = 'https://mirrors.fedoraproject.org/metalink?repo=testing-epel6&arch=$basearch'
    default['yum']['epel-testing']['gpgkey'] = 'http://dl.fedoraproject.org/pub/epel/RPM-GPG-KEY-EPEL-6r'
  end
end

default['yum']['epel-testing']['failovermethod'] = 'priority'
default['yum']['epel-testing']['enabled'] = false
default['yum']['epel-testing']['gpgcheck'] = true
