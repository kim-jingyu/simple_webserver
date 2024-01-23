<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';

    class InquiryRepository {
        public function __construct() {
        }

        public function pagenate(InquiryBoardRequest $inquiryBoardRequest) {
            $conn = null;
            $stmt = null;
            try {   
                $conn = DBConnectionUtil::getConnection();

                $totalCountSql = "SELECT count(*) AS cnt FROM inquiry_board WHERE title LIKE ? AND date_value LIKE ?";
                $stmt = $conn->prepare($totalCountSql);
                $searchWord = $inquiryBoardRequest->getSearchWord();
                $searchWord = '%'.$searchWord.'%';
                $dateValue = $inquiryBoardRequest->getDateValue();
                $dateValue = '%'.$dateValue.'%';
                $stmt->bind_param("ss", $searchWord, $dateValue);
                $stmt->execute();
                $result = $stmt->get_result();
                $totalCnt = $result->fetch_assoc()['cnt'];
                
                $selectSql = "SELECT * FROM inquiry_board WHERE title LIKE ? AND date_value LIKE ?";
                
                if (isset($_GET['sort'])) {
                    $sort = $inquiryBoardRequest->getSort();
                    if ($sort == 'author') {
                        $select_sql .= " order by writer_name desc";
                    } else if ($sort == 'date') {
                        $select_sql .= " order by date_value desc";
                    } else {
                        $select_sql .= " order by id asc";
                    }
                }
                $selectSql .= " LIMIT $startIdxPerPage, $numPerPage";

                $startIdxPerPage = $inquiryBoardRequest->getStartIdxPerPage();
                $numPerPage = $inquiryBoardRequest->getNumPerPage();
                
                $stmt = $conn->prepare($selectSql);
                $stmt->bind_param("ssii", $searchWord, $dateValue, $startIdxPerPage, $numPerPage);
                $stmt->execute();
                $result = $stmt->get_result();

                $inquiryBoardResponse = new InquiryBoardResponse($totalCnt, $result);
                return $inquiryBoardResponse;
            } catch (Exception $e) {
                throw new Exception("Pagenate At Inquiry - DB Exception 발생!");
            }
        }
    }
?>