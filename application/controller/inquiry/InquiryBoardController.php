<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryBoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryBoardRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardViewResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardFixResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/service/inquiry/InquiryBoardService.php';

    class InquiryBoardController {
        public function __construct() {
        }

        public function getInquriyBoard() {
            try {
                $searchWord = filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS);
                $dateValue = filter_var(strip_tags($_GET['dateValue']), FILTER_SANITIZE_SPECIAL_CHARS);

                $numPerPage = 5;

                $pageNow = $_GET['page'] ? filter_var(strip_tags($_GET['page']), FILTER_SANITIZE_SPECIAL_CHARS) : 1;
                $blockNow = floor(($pageNow - 1) / $numPerPage) * $numPerPage;
                $startIdxPerPage = ($pageNow - 1) * $numPerPage;
                $sort = filter_var(strip_tags($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS);

                $inquiryBoardRequest = new InquiryBoardRequest($searchWord, $dateValue, $numPerPage, $startIdxPerPage, $sort);
                
                $inquiryBoardService = new InquiryBoardService();
                $inquiryBoardServiceResponse = $inquiryBoardService->getInquriyBoard($inquiryBoardRequest);
                $totalPages = $inquiryBoardServiceResponse->getTotalPages();
                $boardData = $inquiryBoardServiceResponse->getBoardData();

                $inquiryBoardReponse = new InquiryBoardResponse($searchWord, $dateValue, $pageNow, $blockNow, $sort, $totalPages, $boardData);
                return $inquiryBoardReponse;
            } catch (Exception $e) {
                echo "<script>alert('게시판을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/login/login.html');</script>";
            }
        }

        public function getInquiryBoardView() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
            try {
                $InquiryBoardService = new InquiryBoardService();
                $data = $InquiryBoardService->getInquiryBoardView($boardId);

                $inquiryBoardViewResponse = new InquiryBoardViewResponse($boardId, $data);
                return $inquiryBoardViewResponse;    
            } catch (Exception $e) {
                echo "<script>alert('게시글을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }

        public function getInquiryBoardFix() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
    
            try {
                $nquiryBoardService = new InquiryBoardService();
                $data = $InquiryBoardService->getInquiryBoardFix($boardId);

                $inquiryBoardFixResponse = new InquiryBoardFixResponse($boardId, $data);
                return $inquiryBoardFixResponse;
            } catch (Exception $e) {
                echo "<script>alert('수정폼을 가져오는 도중에 문제가 발생했습니다!');</script>";
                echo "<script>location.replace('/application/view/board/board_view.php?boardId=$boardId');</script>";
            }
        }
    }
?>