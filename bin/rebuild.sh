yes | tugboat rebuild anonymart-$1 -k=10322059
sleep 10
tugboat wait anonymart-$1 --state active
sleep 10

dropletInfo=$(tugboat info anonymart-$1)
dropletInfoParts=($dropletInfo)
ip=${dropletInfoParts[16]}
echo $ip

ssh-keygen -R $ip
sleep 10
ssh root@$ip -i ~/.ssh/digitalocean 'bash -s' < bin/ssh.sh $1