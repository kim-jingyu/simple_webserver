<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/application/repository/inquiry/InquiryBoardRepository.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/application/repository/inquiry/InquiryBoardWriteRequest.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/application/repository/inquiry/InquiryBoardUpdateRequest.php";

    class InquiryBoardService {
        public function __construct() {
        }

        public function write($writerName, $writerPw, $title, $body) {
            $today = date("Y-m-d");

            $inquiryBoardWriteRequest = new InquiryBoardWriteRequest($title, $body, $writerName, $writerPw, $today);
            $inquriyBoardRepository = new InquiryBoardRepository();

            try {
                $boardId = $inquriyBoardRepository->save($inquiryBoardWriteRequest);

                echo "<script>alert('작성이 완료되었습니다.');</script>";
                echo "<script>location.replace('/application/view/inquiry/board_view.php?boardId=$boardId');</script>";
            } catch (Exception $e) {
                echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
                echo "<script>location.replace('/application/view/inquiry/board_write.php');</script>";
            } finally {
                exit();
            }
        }

        public function fix($boardId, $writerName, $writerPw, $title, $body) {
            $today = date("Y-m-d");

            $inquiryBoardUpdateRequest = new InquiryBoardUpdateRequest($boardId, $writerName, $writerPw, $title, $body, $today);
            $inquriyBoardRepository = new InquiryBoardRepository();
            $findPw = $inquriyBoardRepository->findPwByWriterName($writerName);
            if ($findPw != $writerPw) {
                echo "<script>alert('작성자 비밀번호가 틀렸습니다!');</script>";
                echo "<script>location.replace('/application/view/inquiry/board_fix.php?boardId=".$boardId."');</script>";
                exit();
            }

            try {
                $inquriyBoardRepository->update($inquiryBoardUpdateRequest);
                echo "<script>alert('수정이 완료되었습니다.');</script>";
                echo "<script>location.replace('/application/view/inquiry/board_view.php?boardId=".$boardId."');</script>"; 
            } catch (Exception $e) {
                echo "<script>alert('작성 중 오류가 발생했습니다.');</script>";
                echo "<script>location.replace('/application/view/inquiry/board_fix.php?boardId=".$boardId."');</script>";
            } finally {
                exit();
            }
        }
    }
?>