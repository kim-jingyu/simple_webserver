<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/file/FileDownloadService.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/config/jwt/JwtManager.php';

    checkToken();

    function close($message, $boardId) {
        echo "<script>alert('$message')</script>";
        echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
        exit();
    }

    $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

    try {
        $fileDownloadService = new FileDownloadService();

        $fileDownloadService->download($boardId);
        close("파일이 다운로드 되었습니다!", $boardId);
    } catch (IdNotMatchedException $e) {
        close(e->errorMessage(), $boardId);
    } catch (FileNotExsistException $e) {
        close(e->errorMessage(), $boardId);
    } catch (PDOException $e) {
        close("파일 다운로드 실패!", $boardId);
    } catch (Exception $e) {
        close($e->getMessage(), $boardId);
    }    
?>