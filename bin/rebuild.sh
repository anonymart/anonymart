yes | tugboat rebuild anonymart -k=11861819
sleep 10
tugboat wait anonymart --state active
sleep 10
ssh-keygen -R 45.55.192.76
sleep 10
ssh root@45.55.192.76 -i ~/.ssh/digitalocean 'bash -s' < bin/ssh.sh