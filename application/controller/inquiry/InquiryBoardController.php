<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryBoardRepository.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryBoardRequest.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquriyBoardViewResponse.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/controller/inquiry/InquiryBoardFixResponse.php';

    class InquiryBoardController {
        public function __construct() {
        }

        public function getInquriyBoard() {
            $searchWord = filter_var(strip_tags($_GET['search']), FILTER_SANITIZE_SPECIAL_CHARS);
            $dateValue = filter_var(strip_tags($_GET['dateValue']), FILTER_SANITIZE_SPECIAL_CHARS);

            $numPerPage = 5;

            $pageNow = $_GET['page'] ? filter_var(strip_tags($_GET['page']), FILTER_SANITIZE_SPECIAL_CHARS) : 1;
            $blockNow = floor(($pageNow - 1) / $numPerPage) * $numPerPage;
            $startIdxPerPage = ($pageNow - 1) * $numPerPage;
            $sort = filter_var(strip_tags($_GET['sort']), FILTER_SANITIZE_SPECIAL_CHARS);

            $inquiryBoardRequest = new InquiryBoardRequest($searchWord, $dateValue, $numPerPage, $startIdxPerPage, $sort);
            try {
                
            } catch (\Throwable $th) {
                //throw $th;
            }
        }

        public function getInquiryBoardView() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);

            $inquiryBoardRepository = new InquiryBoardRepository();
            $result = $inquiryBoardRepository->findById($boardId);

            $inquiryBoardViewResponse = new InquriyBoardViewResponse($boardId, $result);
            return $inquiryBoardViewResponse;
        }

        public function getInquiryBoardFix() {
            $boardId = filter_var(strip_tags($_GET['boardId']), FILTER_SANITIZE_SPECIAL_CHARS);
    
            $inquiryBoardRepository = new InquiryBoardRepository();
            $result = $inquiryBoardRepository->findById($boardId);
            
            $inquiryBoardFixResponse = new InquiryBoardFixResponse($boardId, $result);
            return $inquiryBoardFixResponse;
        }
    }
?>