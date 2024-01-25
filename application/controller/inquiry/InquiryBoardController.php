<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'';

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
            $inquiryRepository = new InquiryRepository();
            $inquiryBoardResponse = $inquiryRepository->pagenate($inquiryBoardRequest);

            $totalCnt = $inquiryBoardResponse->getTotalCnt();
            $totalPages = ceil($totalCnt / $numPerpage);
            $result = $inquiryBoardResponse->getResult();

            $inquiryBoardResponse = new InquiryBoardResponse($searchWord, $dateValue, $blockNow, $sort, $totalPages, $result);
            return $inquiryBoardResponse;
        }
    }
?>