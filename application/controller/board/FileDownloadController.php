<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/file/FileDownloadService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    $fileDownloadService = new FileDownloadService();
    $fileDownloadService->download($boardId);
?>