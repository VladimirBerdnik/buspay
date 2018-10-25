<?php
$gitDir=realpath(__DIR__.'/../.git');
if (!is_dir($gitDir)) {
    echo ".git directory not found, skip git-hook installation\n";
    exit;
}
$hooksDir = "$gitDir/hooks";
if (!is_dir($hooksDir)) {
    mkdir($hooksDir, 0775, true);
}
$hooksDir = realpath($hooksDir);
$preCommitSrc = realpath(__DIR__.'/pre-commit');
copy($preCommitSrc, "$hooksDir/pre-commit");
copy($preCommitSrc, "$hooksDir/pre-push");
chmod("$hooksDir/pre-commit", 0775);
chmod("$hooksDir/pre-push", 0775);
echo "Pre-commit git hook was installed \n";
