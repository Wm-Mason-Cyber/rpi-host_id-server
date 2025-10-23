# RPI Host ID Server 🤖

This repository provides a quick, standardized setup for a **Raspberry Pi (RPI) Host Identification Server** using Apache2 and PHP.

This server is designed to provide immediate, actionable network information (like the RPI's local IP address and listening ports) to students, minimizing setup time and maximizing practical exercise time in a cybersecurity classroom environment.

## ✨ Features

*   **CyberSec Dashboard:** The main index page (`/`) serves an interactive, terminal-themed dashboard displaying the RPI's local **IP address**, **hostname**, a list of **non-standard users**, and the output of **`netstat -tuln`** (listening ports).
*   **Plaintext IP Endpoint:** A dedicated endpoint (`/plain-ip.php`) that returns **only the RPI's IP address in plaintext**, ideal for simple scripts, LCD displays, or quick command-line queries.
*   **Quick Deployment:** A single shell script automates the installation of Apache2, PHP, file deployment, and critical permission setup.

## 🚀 Quick Setup

These instructions assume you are starting with a fresh installation of **Raspberry Pi OS (or Debian/Ubuntu-based system)** and have cloned this repository onto the RPI.

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/Wm-Mason-Cyber/rpi-host_id-server.git
    cd rpi-host_id-server
    ```

2.  **Execute the Setup Script:**
    The script will install Apache2 and PHP, deploy the files, and set necessary file permissions.
    ```bash
    chmod +x setup.sh
    ./setup.sh
    ```

3.  **Verify the Server:**
    The script will automatically start the Apache2 service. The server is now running!

## 🌐 Usage Endpoints

Once the setup is complete, you can access the RPI from any machine on the same network:

| Endpoint | Purpose | Example URL |
| :--- | :--- | :--- |
| **CyberSec Dashboard** | Primary dashboard showing IP and ports. | `http://<RPI-IP-Address>/` |
| **Plaintext IP** | Returns only the IP address as text. | `http://<RPI-IP-Address>/plain-ip.php` |
| **Original Default Page** | The standard Apache2 `index.html` file, moved. | `http://<RPI-IP-Address>/default.html` |

## ⚙️ How it Works (Technical Details)

The `setup.sh` script performs several important actions:

1.  **Installation:** Installs `apache2` and `php` with the necessary modules.
2.  **File Deployment:** Copies `dashboard.php` and `plain-ip.php` to the web root (`/var/www/html`).
3.  **Default Page Swap:** Moves the original `/var/www/html/index.html` to `/var/www/html/default.html` and creates a **symbolic link** so that `/index.html` points to **`dashboard.php`**.
4.  **Permission Management (Crucial for CyberSec Labs):**
    * The PHP files use `shell_exec("sudo netstat...")` and `shell_exec("sudo hostname...")` to retrieve server-side information.
    * To allow the web user (`www-data`) to run these commands without a password, the script adds an entry to `/etc/sudoers.d/apache_cmds` that explicitly permits `www-data` to run only the following commands with `NOPASSWD`:
        * `/usr/bin/netstat -tuln`
        * `/usr/bin/hostname -I`

## 💡 Recommendation: Consistent Host Identification

To simplify connecting to these headless RPIs, especially in a lab setting, we recommend setting a unique, memorable **hostname** for each unit (e.g., `rpi-01`, `rpi-alice`).

This often allows students to connect via the hostname directly, such as:

* **SSH:** `ssh pi@rpi-01`
* **Browser:** `http://rpi-01.local/` (using mDNS/Avahi, which is often enabled by default)

Using hostnames eliminates the constant need to track changing IP addresses.
