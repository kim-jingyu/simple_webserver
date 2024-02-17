<?php
    require_once $_SERVER['DOCUMENT_ROOT']."/application/repository/inquiry/InquiryBoardRepository.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/application/repository/inquiry/InquiryBoardWriteRequest.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/application/repository/inquiry/InquiryBoardUpdateRequest.php";
    require_once $_SERVER['DOCUMENT_ROOT']."/application/service/inquiry/InquiryBoardServiceResponse.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';

    class InquiryBoardService {
        public function __construct() {
        }

        public function getInquriyBoard(InquiryBoardRequest $inquiryBoardRequest) {
            $conn = DBConnectionUtil::getConnection();
            $inquriyBoardRepository = new InquiryBoardRepository();
            try {
                $conn->beginTransaction();
                $inquiryPagenateResponse = $inquiryBoardRepository->pagenate($inquiryBoardRequest);

                $totalCnt = $inquiryPagenateResponse->getTotalCnt();
                $numPerPage = $inquiryBoardRequest->getNumPerPage();
                $totalPages = ceil($totalCnt / $numPerPage);
                $boardData = $inquiryPagenateResponse->getBoardData();

                $inquiryBoardServiceResponse = new InquiryBoardServiceResponse($totalPages, $boardData);
                $conn->commit();
                return $inquiryBoardServiceResponse;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function getInquiryBoardView($boardId) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $inquiryBoardRepository = new InquiryBoardRepository();
                $data = $inquiryBoardRepository->findById($conn, $boardId);
                $conn->commit();
                return $data;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
        }

        public function getInquiryBoardFix($boardId) {
            $conn = DBConnectionUtil::getConnection();
            try {
                $conn->beginTransaction();

                $inquiryBoardRepository = new InquiryBoardRepository();
                $data = $inquiryBoardRepository->findById($conn, $boardId);
                $conn->commit();
                return $data;
            } catch (Exception $e) {
                $conn->rollback();
                throw $e;
            } finally {
                if ($conn != null) {
                    $conn = null;
                }
            }
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

            $inquiryBoardUpdateRequest = new InquiryBoardUpdateRequest($boardId, $title, $body, $today);
            $inquriyBoardRepository = new InquiryBoardRepository();
            $findName = $inquriyBoardRepository->findWriterNameById($boardId);
            if ($findName != $writerName) {
                echo "<script>alert('작성자 이름이 틀렸습니다!');</script>";
                echo "<script>location.replace('/application/view/inquiry/board_fix.php?boardId=".$boardId."');</script>";
                exit();
            }

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