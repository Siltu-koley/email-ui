Vagrant.configure(2) do |config|
    config.vm.define "controller" do |controller|
      controller.vm.box = "ubuntu/jammy64"
      controller.vm.hostname = "email-ui"
      controller.vm.box_check_update = false
      controller.vm.network "private_network", ip: "192.168.62.102"
      controller.vm.synced_folder "..", "/mnt/components/"
      controller.vm.provider "virtualbox" do |vb|
      vb.memory = "4096"
    end
    controller.vm.provision "shell", inline: <<-SHELL
      export DEBIAN_FRONTEND=noninteractive
      rm /etc/resolv.conf

      echo "nameserver 8.8.8.8" > /etc/resolv.conf

      sudo apt-get update

      sudo apt-get -y install \
        apt-transport-https \
        ca-certificates \
        curl \
        gnupg-agent \
        software-properties-common

      curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -

      sudo apt-key fingerprint 0EBFCD88

      sudo add-apt-repository \
         "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
         $(lsb_release -cs) \
         stable"
      sudo apt-get update

      sudo apt-get -y install nodejs jq docker-ce docker-compose gnupg2 pass sshpass

      docker volume create portainer_data

      sudo chmod 0777 -R /mnt/components/agent/storage
      
      # cd /mnt/components/laravel && docker-compose up -d

    SHELL
  end
end