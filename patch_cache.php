<?php
$dir = new RecursiveDirectoryIterator('c:\xampp\htdocs\Halimaw_Siomai\app\Views');
$iterator = new RecursiveIteratorIterator($dir);

// This script forces the browser to kill the back button history
// by constantly pushing the current state forward.
$dashboardScript = <<<EOT
    <!-- DISABLE BROWSER BACK/FORWARD BUTTONS COMPLETELY -->
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <script>
        // Push an empty state immediately
        history.pushState(null, null, location.href);
        // If the user tries to go back, instantly push them forward again
        window.onpopstate = function () {
            history.go(1);
        };
        
        function enforceClientAuth() {
            if (localStorage.getItem('auth_status') === 'logged_out') {
                document.documentElement.style.display = 'none';
                if(document.body) document.body.style.display = 'none';
                window.location.replace('/Halimaw_Siomai/index.php/login?blocked=1&cb=' + new Date().getTime());
            }
        }
        enforceClientAuth();
        window.addEventListener('pageshow', enforceClientAuth);
    </script>
EOT;

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $filename = $file->getFilename();
        
        if (in_array($filename, ['login.php', 'adminlogin.php', 'userlogin.php', 'register.php'])) {
            continue; // Skip login pages for this specific pushState block
        }

        if (stripos($content, '</head>') !== false) {
            // Remove previous ultimate script
            $content = preg_replace('/<!-- ULTIMATE CLIENT-SIDE AUTH ENFORCEMENT.*?<\/script>/is', '', $content);
            $content = preg_replace('/<!-- DISABLE BROWSER BACK\/FORWARD BUTTONS COMPLETELY.*?<\/script>/is', '', $content);
            
            $newContent = str_ireplace('</head>', "\n" . $dashboardScript . "\n</head>", $content);
            file_put_contents($file->getPathname(), $newContent);
            echo "Added Back Button Killer to: " . $filename . "\n";
        }
    }
}
?>
