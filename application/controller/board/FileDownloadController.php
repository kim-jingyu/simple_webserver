<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/file/FileDownloadService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    function close($message, $boardId) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
        exit();
    }

    try {
        $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

        $fileDownloadService = new FileDownloadService();

        $fileDownloadService->download($boardId);
        close("파일이 다운로드 되었습니다!", $boardId);
    } catch (IdNotMatchedException $e) {
        close(e->errorMessage(), $boardId);
        throw $e;
    } catch (FileNotExsistException $e) {
        close(e->errorMessage(), $boardId);
        throw $e;
    } catch (PDOException $e) {
        close("파일 다운로드 실패!", $boardId);
        throw $e;
    }
    
?>