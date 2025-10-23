<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raspberry Pi - CyberSec Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto Mono', monospace;
        }
        .scanline-effect {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.1) 50.1%, rgba(0,0,0,0) 100%);
            background-size: 100% 4px;
            animation: scan 10s linear infinite;
        }
        @keyframes scan {
            0% { background-position: 0 0; }
            100% { background-position: 0 100vh; }
        }
        .terminal-glow {
            text-shadow: 0 0 5px #00ff00, 0 0 10px #00ff00;
        }
    </style>
</head>
<body class="bg-gray-900 text-green-400 min-h-screen p-4 sm:p-8">
    <div class="scanline-effect"></div>
    <div class="max-w-4xl mx-auto z-10 relative">

        <header class="border-b-2 border-green-700 pb-4 mb-8">
            <h1 class="text-3xl sm:text-5xl font-bold terminal-glow">Raspberry Pi // CyberSec Dashboard</h1>
            <p class="text-green-500 mt-2">STATUS: <span class="font-bold text-green-300">SECURE & ONLINE</span></p>
        </header>

        <main>
            <!-- IP Address Section -->
            <div class="bg-gray-800 border-2 border-green-800 rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-bold mb-4 terminal-glow">[ Network Interface ]</h2>
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9V3m-9 9h18" />
                    </svg>
                    <div>
                        <p class="text-sm text-green-600">Local IP Address:</p>
                        <p class="text-2xl font-bold text-white">
                            <?php
                                // Execute a command to get the primary IP address
                                $ipAddress = shell_exec("sudo hostname -I | awk '{print $1}'");
                                echo trim($ipAddress) ?: 'Not Found';
                            ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Open Ports Section -->
            <div class="bg-gray-800 border-2 border-green-800 rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-4 terminal-glow">[ Listening Ports ]</h2>
                <p class="text-sm text-green-600 mb-4">Actively listening TCP/UDP network ports. (via <code>netstat -tuln</code>)</p>
                <div class="bg-black rounded p-4 h-96 overflow-auto font-mono text-sm">
                    <pre><?php
                        // Execute netstat command to find listening ports. The 'www-data' user (which apache runs as)
                        // may need permissions for this. If this section is empty, see setup instructions.
                        $output = shell_exec("sudo netstat -tuln");

                        if ($output) {
                            echo htmlspecialchars($output);
                        } else {
                            echo "Could not execute 'netstat'. Check server permissions.";
                        }
                    ?></pre>
                </div>
            </div>
        </main>

        <footer class="text-center mt-12 text-green-700 text-sm">
            <p>Generated on: <?php echo date('Y-m-d H:i:s'); ?> (UTC)</p>
        </footer>

    </div>
</body>
</html>
