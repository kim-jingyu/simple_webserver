<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/connection/DBConnectionUtil.php';
    require_once $_SERVER['DOCUMENT_ROOT'].'/application/repository/inquiry/InquiryPagenateResponse.php';

    class InquiryBoardRepository {
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
                $selectSql .= " LIMIT ?, ?";

                $startIdxPerPage = $inquiryBoardRequest->getStartIdxPerPage();
                $numPerPage = $inquiryBoardRequest->getNumPerPage();
                
                $stmt = $conn->prepare($selectSql);
                $stmt->bind_param("ssii", $searchWord, $dateValue, $startIdxPerPage, $numPerPage);
                $stmt->execute();
                $result = $stmt->get_result();

                $inquiryPagenateResponse = new InquiryPagenateResponse($totalCnt, $result);
                return $inquiryPagenateResponse;
            } catch (Exception $e) {
                throw new Exception("Pagenate At Inquiry - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function findById($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "select * from inquiry_board where id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result;
            } catch (Exception $e) {
                throw new Exception("FindById At Inquiry - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function findPwByWriterName($writerName) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "select writer_pw from inquiry_board where writer_name= ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $writerName);
                $stmt->execute();
                $pw = $stmt->get_result()->fetch_assoc()['writer_pw'];
                return $pw;
            } catch (Exception $e) {
                throw new Exception("FindPwByWriterName At Inquiry - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function update(InquiryBoardUpdateRequest $inquiryBoardUpdateRequest) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "update inquiry_board set title = ?, body = ?, writer_name = ?, writer_pw = ?, date_value = ? where id = ?";
                $stmt = $conn->prepare($sql);
                $title = $inquiryBoardUpdateRequest->getTitle();
                $body = $inquiryBoardUpdateRequest->getBody();
                $writerName = $inquiryBoardUpdateRequest->getWriterName();
                $writerPw = $inquiryBoardUpdateRequest->getWriterPw();
                $today = $inquiryBoardUpdateRequest->getToday();
                $boardId = $inquiryBoardUpdateRequest->getBoardId();
                $stmt->bind_param("sssssi", $title, $body, $writerName, $writerPw, $today, $boardId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Update At Inquiry - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function save(InquiryBoardWriteRequest $inquiryBoardWriteRequest) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "insert into inquiry_board (title, body, writer_name, writer_pw, date_value) values (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $title = $inquiryBoardWriteRequest->getTitle();
                $body = $inquiryBoardWriteRequest->getBody();
                $writerName = $inquiryBoardWriteRequest->getWriterName();
                $writerPw = $inquiryBoardWriteRequest->getWriterPw();
                $today = $inquiryBoardWriteRequest->getToday();
                $stmt->bind_param("sssss", $title, $body, $writerName, $writerPw, $today);
                $stmt->execute();

                $boardId = $conn->insert_id;
                return $boardId;
            } catch (Exception $e) {
                throw new Exception("Save At Inquiry - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }

        public function deleteById($boardId) {
            $conn = null;
            $stmt = null;
            try {
                $conn = DBConnectionUtil::getConnection();
                $sql = "delete from inquiry_board where id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $boardId);
                $stmt->execute();
            } catch (Exception $e) {
                throw new Exception("Delete At Inquiry - DB Exception 발생!");
            } finally {
                if ($stmt != null) {
                    $stmt->close();
                }

                if ($conn != null) {
                    $conn->close();
                }
            }
        }
    }
?>