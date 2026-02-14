# Public Directory Migration Script
# This script reorganizes your public directory to match the modular architecture

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Public Directory Migration Script" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Step 1: Create new directory structure
Write-Host "Step 1: Creating new directory structure..." -ForegroundColor Yellow

$directories = @(
    "public\modules",
    "public\modules\school",
    "public\modules\school\css",
    "public\modules\school\js",
    "public\modules\school\images",
    "public\modules\school\uploads",
    "public\modules\mylife",
    "public\modules\mylife\css",
    "public\modules\mylife\js",
    "public\modules\mylife\images",
    "public\modules\mylife\uploads",
    "public\shared",
    "public\shared\css",
    "public\shared\js",
    "public\shared\images",
    "public\shared\fonts"
)

foreach ($dir in $directories) {
    if (!(Test-Path $dir)) {
        New-Item -ItemType Directory -Path $dir -Force | Out-Null
        Write-Host "  Created: $dir" -ForegroundColor Green
    } else {
        Write-Host "  Exists: $dir" -ForegroundColor Gray
    }
}

Write-Host ""

# Step 2: Move School-related uploads
Write-Host "Step 2: Moving School module uploads..." -ForegroundColor Yellow

if (Test-Path "public\students") {
    Move-Item "public\students" "public\modules\school\uploads\students" -Force
    Write-Host "  Moved: students -> modules/school/uploads/students" -ForegroundColor Green
}

if (Test-Path "public\teachers") {
    Move-Item "public\teachers" "public\modules\school\uploads\teachers" -Force
    Write-Host "  Moved: teachers -> modules/school/uploads/teachers" -ForegroundColor Green
}

if (Test-Path "public\users") {
    Move-Item "public\users" "public\modules\school\uploads\users" -Force
    Write-Host "  Moved: users -> modules/school/uploads/users" -ForegroundColor Green
}

Write-Host ""

# Step 3: Create .htaccess for upload security
Write-Host "Step 3: Creating security .htaccess files..." -ForegroundColor Yellow

$htaccessContent = @"
# Prevent PHP execution in upload directories
<FilesMatch "\.(php|php3|php4|php5|phtml)$">
    Order Deny,Allow
    Deny from all
</FilesMatch>
"@

$uploadDirs = @(
    "public\modules\school\uploads",
    "public\modules\mylife\uploads"
)

foreach ($dir in $uploadDirs) {
    $htaccessPath = Join-Path $dir ".htaccess"
    Set-Content -Path $htaccessPath -Value $htaccessContent
    Write-Host "  Created: $htaccessPath" -ForegroundColor Green
}

Write-Host ""

# Step 4: Create .gitkeep files
Write-Host "Step 4: Creating .gitkeep files..." -ForegroundColor Yellow

$gitkeepDirs = @(
    "public\modules\school\css",
    "public\modules\school\js",
    "public\modules\school\images",
    "public\modules\mylife\css",
    "public\modules\mylife\js",
    "public\modules\mylife\images",
    "public\shared\css",
    "public\shared\js",
    "public\shared\images",
    "public\shared\fonts"
)

foreach ($dir in $gitkeepDirs) {
    $gitkeepPath = Join-Path $dir ".gitkeep"
    New-Item -ItemType File -Path $gitkeepPath -Force | Out-Null
    Write-Host "  Created: $gitkeepPath" -ForegroundColor Green
}

Write-Host ""

# Step 5: Summary
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Migration Complete!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "New Structure:" -ForegroundColor Yellow
Write-Host "  public/modules/school/uploads/students/" -ForegroundColor White
Write-Host "  public/modules/school/uploads/teachers/" -ForegroundColor White
Write-Host "  public/modules/school/uploads/users/" -ForegroundColor White
Write-Host "  public/modules/mylife/" -ForegroundColor White
Write-Host "  public/shared/" -ForegroundColor White
Write-Host ""
Write-Host "Next Steps:" -ForegroundColor Yellow
Write-Host "  1. Update asset references in blade templates" -ForegroundColor White
Write-Host "  2. Update file upload paths in controllers" -ForegroundColor White
Write-Host "  3. Test file uploads for each module" -ForegroundColor White
Write-Host "  4. See PUBLIC_DIRECTORY_GUIDE.md for details" -ForegroundColor White
Write-Host ""
Write-Host "Note: assets/ and template/ directories were NOT moved." -ForegroundColor Cyan
Write-Host "Move them manually to public/shared/ if needed." -ForegroundColor Cyan
Write-Host ""
