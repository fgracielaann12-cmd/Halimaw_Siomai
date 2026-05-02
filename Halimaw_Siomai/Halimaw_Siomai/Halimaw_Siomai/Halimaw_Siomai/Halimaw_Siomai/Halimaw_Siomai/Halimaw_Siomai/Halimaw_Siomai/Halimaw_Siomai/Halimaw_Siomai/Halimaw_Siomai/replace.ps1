$files = Get-ChildItem -Path app\Views -Recurse -Filter *.php | Select-String -Pattern 'admin/sales' | Select-Object -ExpandProperty Path -Unique

foreach ($f in $files) {
    if ($f -match 'partials\\admin_sidebar.php') { continue; }
    
    $content = Get-Content -Path $f -Raw
    
    if ($content -match '(?s)<!-- Mobile Menu Toggle -->.*?</nav>') {
        $replacement = "<?= view('partials/admin_sidebar') ?>"
        $newContent = [regex]::Replace($content, '(?s)<!-- Mobile Menu Toggle -->.*?</nav>', $replacement)
        Set-Content -Path $f -Value $newContent -NoNewline
        Write-Host "Updated $($f)"
    }
}
