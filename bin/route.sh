#!/bin/sh
# destinations you don’t want routed through Tor
NON_TOR="192.168.1.0/24"
# the UID Tor runs as
TOR_UID=$(id -u debian-tor)
# Tor’s TransPort
TRANS_PORT="9040"
iptables -F
iptables -t nat -F
iptables -t nat -A OUTPUT -m owner --uid-owner $TOR_UID -j RETURN
iptables -t nat -A OUTPUT -p udp --dport 53 -j REDIRECT --to-ports 53
for NET in $NON_TOR 127.0.0.0/9 127.128.0.0/10; do
iptables -t nat -A OUTPUT -d $NET -j RETURN
done
iptables -t nat -A OUTPUT -p tcp --syn -j REDIRECT --to-ports $TRANS_PORT
iptables -A OUTPUT -m state --state ESTABLISHED,RELATED -j ACCEPT
for NET in $NON_TOR 127.0.0.0/8; do
iptables -A OUTPUT -d $NET -j ACCEPT
done
iptables -A OUTPUT -m owner --uid-owner $TOR_UID -j ACCEPT
iptables -A OUTPUT -j REJECT