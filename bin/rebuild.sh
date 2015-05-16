yes | tugboat rebuild anonymart2 -k=11861819
sleep 10
tugboat wait anonymart2 --state active
sleep 10
ssh-keygen -R 104.236.227.109
sleep 10
ssh root@104.236.227.109 -i ~/.ssh/digitalocean 'bash -s' < bin/ssh.sh