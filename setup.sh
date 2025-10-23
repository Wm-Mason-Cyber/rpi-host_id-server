#!/bin/bash

# --- RPI Host ID Server Setup Script ---

# 1. Update and install necessary packages
echo "Updating package list and installing Apache2 and PHP..."
sudo apt update
sudo apt install -y apache2 php libapache2-mod-php

# 2. Enable PHP execution in Apache (if it wasn't already)
sudo a2enmod php

# 3. Stop Apache to prevent issues during file changes
echo "Stopping Apache service..."
sudo systemctl stop apache2

# 4. Define the web root directory
WEB_ROOT="/var/www/html"
REPO_DIR="$(pwd)" # Assuming the script is run from the cloned repo directory

# 5. Handle the default index.html (Request 1)
echo "Moving existing default index.html to default.html..."
if [ -f "${WEB_ROOT}/index.html" ]; then
    sudo mv "${WEB_ROOT}/index.html" "${WEB_ROOT}/default.html"
else
    echo "No existing index.html found. Skipping move."
fi

# Create your default.html placeholder if it doesn't exist
if [ ! -f "${WEB_ROOT}/default.html" ]; then
    sudo cp "${REPO_DIR}/default.html" "${WEB_ROOT}/default.html"
fi

# 6. Copy your files to the web root
echo "Copying dashboard.php and plain-ip.php to web root..."
sudo cp "${REPO_DIR}/dashboard.php" "${WEB_ROOT}/dashboard.php"
sudo cp "${REPO_DIR}/plain-ip.php" "${WEB_ROOT}/plain-ip.php"

# 7. Create a symbolic link for the default page (index.html -> dashboard.php) (Request 2)
echo "Creating symbolic link for dashboard as default page..."
sudo ln -sf "${WEB_ROOT}/dashboard.php" "${WEB_ROOT}/index.html"

# 8. Set permissions for Apache's www-data user to run netstat and hostname
# This is a critical security step for the netstat function in the dashboard.
echo "Setting sudo permissions for www-data to run netstat, hostname, cut, and awk..."
# This allows www-data to execute netstat -tuln and hostname and the user command without a password
echo "www-data ALL=(ALL) NOPASSWD: /usr/bin/netstat -tuln, /usr/bin/hostname, /usr/bin/cut, /usr/bin/awk" | sudo tee /etc/sudoers.d/apache_cmds

# In the PHP files, we need to prefix the shell_exec commands with 'sudo'

# Update dashboard.php command
# $ipAddress = shell_exec("sudo hostname -I | awk '{print \$1}'");
# $output = shell_exec("sudo netstat -tuln");

# Update plain-ip.php command
# $ipAddress = shell_exec("sudo hostname -I | awk '{print \$1}'");


# To make the script truly quick-start, we'll patch the PHP files to use 'sudo'
# We'll use sed to add 'sudo' to the commands in the files you copied.
echo "Patching dashboard.php and plain-ip.php to use 'sudo'..."
sudo sed -i 's/shell_exec("hostname/shell_exec("sudo hostname/g' "${WEB_ROOT}/dashboard.php"
sudo sed -i 's/shell_exec("netstat/shell_exec("sudo netstat/g' "${WEB_ROOT}/dashboard.php"
sudo sed -i 's/shell_exec("cut/shell_exec("sudo cut/g' "${WEB_ROOT}/dashboard.php"
sudo sed -i 's/shell_exec("awk/shell_exec("sudo awk/g' "${WEB_ROOT}/dashboard.php"
sudo sed -i 's/shell_exec("hostname/shell_exec("sudo hostname/g' "${WEB_ROOT}/plain-ip.php"


# 9. Restart Apache service
echo "Restarting Apache service and enabling it..."
sudo systemctl start apache2
sudo systemctl enable apache2

echo "--- Setup Complete! ---"
echo "Access the dashboard at http://<RaspberryPiIP>/"
echo "Access the plaintext IP endpoint at http://<RaspberryPiIP>/plain-ip.php"
