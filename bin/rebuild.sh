yes | tugboat rebuild anonymart -k=11861819
sleep 10
tugboat wait anonymart --state active
sleep 10
ssh-keygen -R 104.236.98.89
sleep 10
ssh root@104.236.98.89 -i ~/.ssh/digitalocean 'bash -s' < bin/ssh.sh