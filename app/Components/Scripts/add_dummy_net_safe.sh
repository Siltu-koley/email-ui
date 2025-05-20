#!/bin/bash

set -e

# Must run as root
if [ "$EUID" -ne 0 ]; then
  echo "❌ Please run as root (sudo)"
  exit 1
fi

# Check argument
if [ $# -ne 1 ]; then
  echo "Usage: $0 <ip-address/CIDR>"
  echo "Example: $0 15.204.14.108/32"
  exit 1
fi

IPADDR=$1
IP=${IPADDR%%/*}  # Extract just the IP (without CIDR)

# Check if IP already exists
if ip addr | grep -qw "$IP"; then
  echo "❌ IP address '$IP' is already in use."
  ip addr | grep "$IP"
  exit 1
fi

# Function to find next unused dummy interface name (ens4+)
find_next_iface() {
  for i in $(seq 4 100); do
    IF="ens$i"
    if ! ip link show "$IF" &>/dev/null && \
       [ ! -e "/etc/systemd/network/10-dummy-${IF}.netdev" ] && \
       [ ! -e "/etc/systemd/network/10-dummy-${IF}.network" ]; then
      echo "$IF"
      return 0
    fi
  done
  echo "❌ No available dummy interface name found." >&2
  exit 1
}

IFACE=$(find_next_iface)

NETDEV_FILE="/etc/systemd/network/10-dummy-${IFACE}.netdev"
NETWORK_FILE="/etc/systemd/network/10-dummy-${IFACE}.network"

echo "📁 Creating dummy interface '$IFACE' with IP '$IPADDR'..."

# Write .netdev file
cat <<EOF > "$NETDEV_FILE"
[NetDev]
Name=$IFACE
Kind=dummy
EOF

# Write .network file
cat <<EOF > "$NETWORK_FILE"
[Match]
Name=$IFACE

[Network]
Address=$IPADDR
EOF

# Enable and restart systemd-networkd
echo "🔄 Restarting systemd-networkd..."
systemctl enable systemd-networkd > /dev/null 2>&1 || true
systemctl restart systemd-networkd

sleep 2

# Verify
if ip addr show "$IFACE" | grep -qw "$IP"; then
  echo "✅ Interface '$IFACE' created and assigned IP '$IPADDR'."
else
  echo "❌ Interface creation failed. Check: journalctl -u systemd-networkd"
  exit 1
fi
