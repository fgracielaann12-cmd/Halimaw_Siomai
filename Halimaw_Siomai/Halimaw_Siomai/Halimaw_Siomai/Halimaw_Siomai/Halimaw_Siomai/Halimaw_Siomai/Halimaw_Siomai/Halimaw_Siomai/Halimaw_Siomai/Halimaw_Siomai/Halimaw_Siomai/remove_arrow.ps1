$files = Get-ChildItem -Path app\Views -Recurse -Filter *.php | Select-Object -ExpandProperty FullName

foreach ($f in $files) {
    $content = Get-Content -Path $f -Raw
    $modified = $false

    if ($content -match 'z-index:\s*1001\s*;') {
        $content = [regex]::Replace($content, 'z-index:\s*1001\s*;', 'z-index: 998;')
        $modified = $true
    }

    if ($content -match '#sidebar .navbar-brand \{ padding-left: 55px; font-size: 1rem; padding-right: 15px; \}') {
        $content = $content.Replace('#sidebar .navbar-brand { padding-left: 55px; font-size: 1rem; padding-right: 15px; }', '')
        $modified = $true
    }

    if ($content -match '#sidebar .navbar-brand img \{ width: 34px; height: 34px; \}') {
        $content = $content.Replace('#sidebar .navbar-brand img { width: 34px; height: 34px; }', '')
        $modified = $true
    }

    if ($content -match 'mobileMenuToggle\.innerHTML\s*=\s*''<i class="bi bi-arrow-left"></i>'';') {
        $content = [regex]::Replace($content, 'mobileMenuToggle\.innerHTML\s*=\s*''<i class="bi bi-arrow-left"></i>'';', '// removed arrow')
        $modified = $true
    }

    if ($content -match 'mobileMenuToggle\.innerHTML\s*=\s*''<i class="bi bi-list"></i>'';') {
        $content = [regex]::Replace($content, 'mobileMenuToggle\.innerHTML\s*=\s*''<i class="bi bi-list"></i>'';', '// removed arrow')
        $modified = $true
    }

    if ($modified) {
        Set-Content -Path $f -Value $content -NoNewline
        Write-Host "Updated $($f)"
    }
}
